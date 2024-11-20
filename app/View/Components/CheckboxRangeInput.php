<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;

class CheckboxRangeInput extends Component
{
    public $label;
    public $id;
    public $name;
    public $min;
    public $max;
    public $step;
    public $checked;
    public $tooltip;

    public function __construct($label, $id, $name, $min = 0, $max = 1, $step = 0.1, $checked = false, $tooltip = '')
    {
        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->checked = $checked;
        $this->tooltip = $tooltip;
    }

    public function render()
    {
        return view('components.checkbox-range-input');
    }
}
