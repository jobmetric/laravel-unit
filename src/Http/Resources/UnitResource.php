<?php

namespace JobMetric\Unit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JobMetric\Unit\Models\UnitRelation;

/**
 * @property mixed id
 * @property mixed type
 * @property mixed value
 * @property mixed status
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @property mixed translations
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
        global $translationLocale;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'translations' => translationResourceData($this->translations, $translationLocale),

            'unitRelations' => $this->whenLoaded('unitRelations', function () {
                return UnitRelationResource::collection($this->unitRelations);
            }),
        ];
    }
}
