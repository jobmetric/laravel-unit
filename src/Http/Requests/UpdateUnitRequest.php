<?php

namespace JobMetric\Unit\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\Location\Models\LocationCountry;
use JobMetric\Location\Rules\CheckExistNameRule;
use JobMetric\Translation\Rules\TranslationFieldExistRule;
use JobMetric\Unit\Models\Unit;

class UpdateUnitRequest extends FormRequest
{
    public int|null $unit_id = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (is_null($this->unit_id)) {
            $unit_id = $this->route()->parameter('unit')?->id;
        } else {
            $unit_id = $this->unit_id;
        }

        return [
            'value' => 'sometimes|decimal:15',
            'status' => 'sometimes|boolean|nullable',

            'translation' => 'sometimes|array',
            'translation.name' => [
                'sometimes',
                'string',
                new TranslationFieldExistRule(Unit::class, 'name', unit_id: $unit_id),
            ],
            'translation.code' => 'sometimes|string',
            'translation.position' => 'sometimes|string|in:left,right|nullable',
            'translation.description' => 'sometimes|string|nullable',
        ];
    }

    /**
     * Set unit id for validation
     *
     * @param int $unit_id
     * @return static
     */
    public function setUnitId(int $unit_id): static
    {
        $this->unit_id = $unit_id;

        return $this;
    }
}
