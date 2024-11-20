<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MultiRangeInput extends Component
{
    public $label;
    public $id;
    public $name;
    public $min;
    public $max;
    public $step;
    public $checked;
    public $tooltip;
    public $firstRangeId;
    public $secondRangeId;
    public $firstRangeName;
    public $secondRangeName;

    public function __construct($label, $id, $name, $firstRangeId, $secondRangeId, $firstRangeName, $secondRangeName, $min = 0, $max = 1, $step = 0.1, $checked = false, $tooltip = '')
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->firstRangeId = $firstRangeId;
        $this->secondRangeId = $secondRangeId;
        $this->firstRangeName = $firstRangeName;
        $this->secondRangeName = $secondRangeName;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->checked = $checked;
        $this->tooltip = $tooltip;

    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.multi-range-input');
    }
}
