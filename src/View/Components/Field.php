<?php

namespace JobMetric\Unit\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use JobMetric\Unit\Models\Unit;

class Field extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public string $dataInput = '',
        public string $dataSelect = '',
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data['type'] = $this->type;
        $units = Unit::ofType($this->type)->with('translations')->get();

        $data['units'] = translationDataSelect($units, 'name');
        $data['dataInput'] = $this->dataInput;
        $data['dataSelect'] = $this->dataSelect;

        DomiPlugins('select2');

        return view('unit::components.field', $data);
    }

    /**
     * Determine if the given option is the currently selected option.
     */
    public function isSelected(string $option): bool
    {
        return $option === $this->dataSelect;
    }
}
