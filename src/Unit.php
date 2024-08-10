<?php

namespace JobMetric\Unit;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\Unit\Events\UnitDeleteEvent;
use JobMetric\Unit\Events\UnitForceDeleteEvent;
use JobMetric\Unit\Events\UnitRestoreEvent;
use JobMetric\Unit\Events\UnitStoreEvent;
use JobMetric\Unit\Events\UnitUpdateEvent;
use JobMetric\Unit\Http\Requests\StoreUnitRequest;
use JobMetric\Unit\Http\Requests\UpdateUnitRequest;
use JobMetric\Unit\Http\Resources\UnitResource;
use JobMetric\Unit\Models\Unit as UnitModel;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class Unit
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Create a new Translation instance.
     *
     * @param Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the specified unit.
     *
     * @param array $filter
     * @param array $with
     * @param string|null $mode
     *
     * @return QueryBuilder
     */
    public function query(array $filter = [], array $with = [], string $mode = null): QueryBuilder
    {
        $fields = [
            'id',
            'type',
            'value',
            'status',
            'created_at',
            'updated_at'
        ];

        $query = QueryBuilder::for(UnitModel::class);

        if ($mode === 'withTrashed') {
            $query->withTrashed();
        }

        if ($mode === 'onlyTrashed') {
            $query->onlyTrashed();
        }

        $query->allowedFields($fields)
            ->allowedSorts($fields)
            ->allowedFilters($fields)
            ->defaultSort('-id')
            ->where($filter);

        if (!empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * Paginate the specified unit.
     *
     * @param array $filter
     * @param int $page_limit
     * @param array $with
     * @param string|null $mode
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(array $filter = [], int $page_limit = 15, array $with = [], string $mode = null): AnonymousResourceCollection
    {
        return UnitResource::collection(
            $this->query($filter, $with, $mode)->paginate($page_limit)
        );
    }

    /**
     * Get all location units.
     *
     * @param array $filter
     * @param array $with
     * @param string|null $mode
     *
     * @return AnonymousResourceCollection
     */
    public function all(array $filter = [], array $with = [], string $mode = null): AnonymousResourceCollection
    {
        return UnitResource::collection(
            $this->query($filter, $with, $mode)->get()
        );
    }

    /**
     * Get the specified unit.
     *
     * @param int $unit_id
     * @param array $with
     * @param string|null $mode
     *
     * @return array
     */
    public function get(int $unit_id, array $with = [], string $mode = null): array
    {
        if ($mode === 'withTrashed') {
            $query = UnitModel::withTrashed();
        } else if ($mode === 'onlyTrashed') {
            $query = UnitModel::onlyTrashed();
        } else {
            $query = UnitModel::query();
        }

        $query->where('id', $unit_id);

        if (!empty($with)) {
            $query->with($with);
        }

        $unit = $query->first();

        if (!$unit) {
            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => [
                    trans('unit::base.validation.object_not_found')
                ],
                'status' => 404
            ];
        }

        return [
            'ok' => true,
            'message' => trans('unit::base.messages.found'),
            'data' => UnitResource::make($unit),
            'status' => 200
        ];
    }

    /**
     * Store the specified unit.
     *
     * @param array $data
     *
     * @return array
     * @throws Throwable
     */
    public function store(array $data): array
    {
        $validator = Validator::make($data, (new StoreUnitRequest)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => $errors,
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
        }

        $unit_count_in_type = UnitModel::query()->where('type', $data['type'])->count();

        if ($unit_count_in_type == 0 && $data['value'] != 1) {
            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => [
                    'form' => [
                        trans('unit::base.validation.unit_type_default_value_error', [
                            'unit' => $data['type']
                        ])
                    ]
                ],
                'status' => 422
            ];
        }

        if ($unit_count_in_type >= 1 && $data['value'] == 1) {
            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => [
                    'form' => [
                        trans('unit::base.validation.unit_type_use_default_value_error', [
                            'unit' => $data['type']
                        ])
                    ]
                ],
                'status' => 422
            ];
        }

        return DB::transaction(function () use ($data) {
            $unit = new UnitModel;
            $unit->type = $data['type'];
            $unit->value = $data['value'];
            $unit->status = $data['status'] ?? true;
            $unit->save();

            $unit->translate(app()->getLocale(), [
                'name' => $data['translation']['name'],
                'code' => $data['translation']['code'],
                'position' => $data['translation']['position'] ?? 'left',
                'description' => $data['translation']['description'] ?? null
            ]);

            event(new UnitStoreEvent($unit, $data));

            return [
                'ok' => true,
                'message' => trans('unit::base.messages.created'),
                'data' => UnitResource::make($unit),
                'status' => 201
            ];
        });
    }

    /**
     * Update the specified unit.
     *
     * @param int $unit_id
     * @param array $data
     *
     * @return array
     */
    public function update(int $unit_id, array $data): array
    {
        $validator = Validator::make($data, (new UpdateUnitRequest)->setUnitId($unit_id)->rules());
        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'ok' => false,
                'message' => trans('unit::base.validation.errors'),
                'errors' => $errors,
                'status' => 422
            ];
        } else {
            $data = $validator->validated();
        }

        return DB::transaction(function () use ($unit_id, $data) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::query()->where('id', $unit_id)->first();

            if (!$unit) {
                return [
                    'ok' => false,
                    'message' => trans('unit::base.validation.errors'),
                    'errors' => [
                        'form' => [
                            trans('unit::base.validation.object_not_found')
                        ]
                    ],
                    'status' => 404
                ];
            }

            if (array_key_exists('value', $data)) {
                if ($unit->value == 1 && $data['value'] != 1) {
                    return [
                        'ok' => false,
                        'message' => trans('unit::base.validation.errors'),
                        'errors' => [
                            'value' => [
                                trans('unit::base.validation.unit_type_cannot_change_default_value')
                            ]
                        ],
                        'status' => 422
                    ];
                }

                $unit->value = $data['value'];
            }

            if (array_key_exists('status', $data)) {
                $unit->status = $data['status'];
            }

            if (array_key_exists('translation', $data)) {
                $trnas = [];
                if (array_key_exists('name', $data['translation'])) {
                    $trnas['name'] = $data['translation']['name'];
                }

                if (array_key_exists('code', $data['translation'])) {
                    $trnas['code'] = $data['translation']['code'];
                }

                if (array_key_exists('position', $data['translation'])) {
                    $trnas['position'] = $data['translation']['position'];
                }

                if (array_key_exists('description', $data['translation'])) {
                    $trnas['description'] = $data['translation']['description'];
                }

                $unit->translate(app()->getLocale(), $trnas);
            }

            $unit->save();

            event(new UnitUpdateEvent($unit, $data));

            return [
                'ok' => true,
                'message' => trans('unit::base.messages.updated'),
                'data' => UnitResource::make($unit),
                'status' => 200
            ];
        });
    }

    /**
     * Delete the specified unit.
     *
     * @param int $unit_id
     *
     * @return array
     */
    public function delete(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::query()->where('id', $unit_id)->first();

            if (!$unit) {
                return [
                    'ok' => false,
                    'message' => trans('location::base.validation.errors'),
                    'errors' => [
                        trans('unit::base.validation.object_not_found')
                    ],
                    'status' => 404
                ];
            }

            event(new UnitDeleteEvent($unit));

            $data = UnitResource::make($unit);

            $unit->delete();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('unit::base.messages.deleted'),
                'status' => 200
            ];
        });
    }

    /**
     * Restore the specified unit.
     *
     * @param int $unit_id
     *
     * @return array
     */
    public function restore(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::onlyTrashed()->where('id', $unit_id)->first();

            if (!$unit) {
                return [
                    'ok' => false,
                    'message' => trans('unit::base.validation.errors'),
                    'errors' => [
                        trans('unit::base.validation.object_not_found')
                    ],
                    'status' => 404
                ];
            }

            event(new UnitRestoreEvent($unit));

            $data = UnitResource::make($unit);

            $unit->restore();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('unit::base.messages.restored'),
                'status' => 200
            ];
        });
    }

    /**
     * Force delete the specified unit.
     *
     * @param int $unit_id
     *
     * @return array
     */
    public function forceDelete(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::onlyTrashed()->where('id', $unit_id)->first();

            if (!$unit) {
                return [
                    'ok' => false,
                    'message' => trans('unit::base.validation.errors'),
                    'errors' => [
                        trans('unit::base.validation.object_not_found')
                    ],
                    'status' => 404
                ];
            }

            event(new UnitForceDeleteEvent($unit));

            $data = UnitResource::make($unit);

            $unit->forceDelete();

            return [
                'ok' => true,
                'data' => $data,
                'message' => trans('location::base.messages.permanently_deleted'),
                'status' => 200
            ];
        });
    }

    /**
     * Change Default Value
     *
     * @param int $unit_id
     *
     * @return array
     */
    public function changeDefaultValue(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::query()->where('id', $unit_id)->first();

            if (!$unit) {
                return [
                    'ok' => false,
                    'message' => trans('unit::base.validation.errors'),
                    'errors' => [
                        trans('unit::base.validation.object_not_found')
                    ],
                    'status' => 404
                ];
            }

            UnitModel::query()->where('type', $unit->type)->get()->each(function (UnitModel $item) use ($unit) {
                $item->value = $item->value / $unit->value;

                $item->save();
            });

            return [
                'ok' => true,
                'message' => trans('unit::base.messages.change_default_value'),
                'data' => UnitResource::make($unit),
                'status' => 200
            ];
        });
    }
}
