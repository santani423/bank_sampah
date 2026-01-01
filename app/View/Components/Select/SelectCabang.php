<?php

namespace App\View\Components\Select;

use App\Models\cabang;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCabang extends Component
{
    /**
     * Create a new component instance.
     */
    public $cabang, $name;
    public function __construct($name = 'cabang')
    {
        //
        $this->cabang = cabang::all();
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select.select-cabang');
    }
}
