<?php

namespace JobMetric\Unit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JobMetric\Translation\Http\Resources\TranslationResource;
use JobMetric\Unit\Models\UnitRelation;

/**
 * @property mixed id
 * @property mixed type
 * @property mixed value
 * @property mixed status
 * @property mixed deleted_at
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @property UnitRelation unitRelations
 */
class UnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'translation' => $this->whenLoaded('translation', function () {
                return TranslationResource::collection($this->translation);
            }),

            'unitRelation' => $this->whenLoaded('unitRelation', function () {
                return UnitRelationResource::collection($this->unitRelation);
            }),
        ];
    }
}
