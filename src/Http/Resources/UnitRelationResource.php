<?php

namespace JobMetric\Unit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JobMetric\Unit\Models\Unit;

/**
 * @property mixed unit_id
 * @property mixed unitable_id
 * @property mixed unitable_type
 * @property mixed type
 * @property mixed created_at
 *
 * @property Unit unit
 * @property mixed unitable
 * @property mixed unitable_resource
 */
class UnitRelationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'unit_id' => $this->unit_id,
            'unitable_id' => $this->unitable_id,
            'unitable_type' => $this->unitable_type,
            'type' => $this->type,
            'created_at' => $this->created_at,

            'unit' => $this->whenLoaded('unit', function () {
                return new UnitResource($this->unit);
            }),

            'unitable' => $this?->unitable_resource
        ];
    }
}
