<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Select extends Component
{
    public $name;
    public $label;
    public $options;

    public function __construct($name, $label, $options)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
    }

    public function render()
    {
        return view('components.forms.select');
    }
}
