<?php

namespace JobMetric\Unit\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use JobMetric\Location\Models\LocationCountry;
use JobMetric\Location\Rules\CheckExistNameRule;
use JobMetric\Translation\Rules\TranslationFieldExistRule;
use JobMetric\Unit\Enums\UnitTypeEnum;
use JobMetric\Unit\Models\Unit;

class StoreUnitRequest extends FormRequest
{
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
        return [
            'type' => 'required|string|in:' . implode(',', UnitTypeEnum::values()),
            'value' => 'required|numeric',
            'status' => 'boolean|nullable',

            'translation' => 'required|array',
            'translation.name' => [
                'string',
                new TranslationFieldExistRule(Unit::class, 'name'),
            ],
            'translation.code' => 'required|string',
            'translation.position' => 'string|in:left,right|nullable',
            'translation.description' => 'string|nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->status ?? true,
            'translation.position' => $this->translation['position'] ?? 'left',
        ]);
    }
}
