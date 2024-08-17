<?php

namespace JobMetric\Unit;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JobMetric\Unit\Events\UnitDeleteEvent;
use JobMetric\Unit\Events\UnitStoreEvent;
use JobMetric\Unit\Events\UnitUpdateEvent;
use JobMetric\Unit\Exceptions\CannotDeleteDefaultValueException;
use JobMetric\Unit\Exceptions\FromAndToMustSameTypeException;
use JobMetric\Unit\Exceptions\UnitNotFoundException;
use JobMetric\Unit\Exceptions\UnitTypeCannotChangeDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeUseDefaultValueException;
use JobMetric\Unit\Exceptions\UnitTypeUsedInException;
use JobMetric\Unit\Http\Requests\StoreUnitRequest;
use JobMetric\Unit\Http\Requests\UpdateUnitRequest;
use JobMetric\Unit\Http\Resources\UnitRelationResource;
use JobMetric\Unit\Http\Resources\UnitResource;
use JobMetric\Unit\Models\Unit as UnitModel;
use JobMetric\Unit\Models\UnitRelation;
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
     * Get the object unit.
     *
     * @param int $unit_id
     *
     * @return Builder|Model
     * @throws Throwable
     */
    public function getObject(int $unit_id): Builder|Model
    {
        $unit = UnitModel::query()->where('id', $unit_id)->first();

        if (!$unit) {
            throw new UnitNotFoundException($unit_id);
        }

        return $unit;
    }

    /**
     * Get the specified unit.
     *
     * @param array $filter
     * @param array $with
     *
     * @return QueryBuilder
     */
    public function query(array $filter = [], array $with = []): QueryBuilder
    {
        $fields = [
            'id',
            'type',
            'value',
            'status',
            'created_at',
            'updated_at'
        ];

        $query = QueryBuilder::for(UnitModel::class)
            ->allowedFields($fields)
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
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(array $filter = [], int $page_limit = 15, array $with = []): AnonymousResourceCollection
    {
        return UnitResource::collection(
            $this->query($filter, $with)->paginate($page_limit)
        );
    }

    /**
     * Get all units.
     *
     * @param array $filter
     * @param array $with
     *
     * @return AnonymousResourceCollection
     */
    public function all(array $filter = [], array $with = []): AnonymousResourceCollection
    {
        return UnitResource::collection(
            $this->query($filter, $with)->get()
        );
    }

    /**
     * Get the specified unit.
     *
     * @param int $unit_id
     * @param array $with
     * @param string|null $locale
     *
     * @return array
     * @throws Throwable
     */
    public function get(int $unit_id, array $with = [], string $locale = null): array
    {
        $query = UnitModel::query()
            ->where('id', $unit_id);

        if (!empty($with)) {
            $query->with($with);
        }

        if (!in_array('translations', $with)) {
            $query->with('translations');
        }

        $unit = $query->first();

        if (!$unit) {
            throw new UnitNotFoundException($unit_id);
        }

        global $translationLocale;
        if (!is_null($locale)) {
            $translationLocale = $locale;
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
            throw new UnitTypeDefaultValueException($data['type']);
        }

        if ($unit_count_in_type >= 1 && $data['value'] == 1) {
            throw new UnitTypeUseDefaultValueException($data['type']);
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
     * @throws Throwable
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
            $unit = UnitModel::find($unit_id);

            if (!$unit) {
                throw new UnitNotFoundException($unit_id);
            }

            if (array_key_exists('value', $data)) {
                if ($unit->value == 1 && $data['value'] != 1) {
                    throw new UnitTypeCannotChangeDefaultValueException;
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
     * @throws Throwable
     */
    public function delete(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::find($unit_id);

            if (!$unit) {
                throw new UnitNotFoundException($unit_id);
            }

            $check_used = $this->hasUsed($unit_id);

            if ($check_used) {
                $count = UnitRelation::query()->where([
                    'unit_id' => $unit_id
                ])->count();

                throw new UnitTypeUsedInException($unit_id, $count);
            }

            if ($unit->value == 1) {
                $unit_count = UnitModel::query()->where('type', $unit->type)->count();

                if ($unit_count > 1) {
                    throw new CannotDeleteDefaultValueException;
                }
            }

            event(new UnitDeleteEvent($unit));

            $data = UnitResource::make($unit);

            $unit->translations()->delete();

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
     * Change Default Value
     *
     * @param int $unit_id
     *
     * @return array
     * @throws Throwable
     */
    public function changeDefaultValue(int $unit_id): array
    {
        return DB::transaction(function () use ($unit_id) {
            /**
             * @var UnitModel $unit
             */
            $unit = UnitModel::find($unit_id);

            if (!$unit) {
                throw new UnitNotFoundException($unit_id);
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

    /**
     * Used In unit
     *
     * @param int $unit_id
     *
     * @return array
     * @throws Throwable
     */
    public function usedIn(int $unit_id): array
    {
        /**
         * @var UnitModel $unit
         */
        $unit = UnitModel::find($unit_id);

        if (!$unit) {
            throw new UnitNotFoundException($unit_id);
        }

        $unit_relations = UnitRelation::query()->where([
            'unit_id' => $unit_id
        ])->get();

        return [
            'ok' => true,
            'message' => trans('unit::base.messages.used_in', [
                'count' => $unit_relations->count()
            ]),
            'data' => UnitRelationResource::collection($unit_relations),
            'status' => 200
        ];
    }

    /**
     * Has Used unit
     *
     * @param int $unit_id
     *
     * @return bool
     * @throws Throwable
     */
    public function hasUsed(int $unit_id): bool
    {
        /**
         * @var UnitModel $unit
         */
        $unit = UnitModel::find($unit_id);

        if (!$unit) {
            throw new UnitNotFoundException($unit_id);
        }

        return UnitRelation::query()->where([
            'unit_id' => $unit_id
        ])->exists();
    }

    /**
     * Convert unit
     *
     * @param int $from_unit_id
     * @param int $to_unit_id
     * @param float $value
     *
     * @return float
     * @throws Throwable
     */
    public function convert(int $from_unit_id, int $to_unit_id, float $value): float
    {
        /**
         * @var UnitModel $from_unit
         */
        $from_unit = UnitModel::find($from_unit_id);

        if (!$from_unit) {
            throw new UnitNotFoundException($from_unit_id);
        }

        /**
         * @var UnitModel $to_unit
         */
        $to_unit = UnitModel::find($to_unit_id);

        if (!$to_unit) {
            throw new UnitNotFoundException($to_unit_id);
        }

        if ($from_unit->type != $to_unit->type) {
            throw new FromAndToMustSameTypeException;
        }

        return $value * $from_unit->value / $to_unit->value;
    }
}
