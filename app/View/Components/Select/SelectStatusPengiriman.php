<?php

namespace App\View\Components\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectStatusPengiriman extends Component
{
    /**
     * Create a new component instance.
     */
    public   $name;
    public function __construct($name = 'status_pengiriman')
    {
        //
        $this->name = $name;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select.select-status-pengiriman');
    }
}
