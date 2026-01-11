<?php

namespace App\View\Components\select;

use App\Models\JenisMetodePenarikan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class selectMetodeBayar extends Component
{
    /**
     * Create a new component instance.
     */

    public $jenisMetodePenarikan;
    public function __construct()
    {
        $this->jenisMetodePenarikan = JenisMetodePenarikan::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select.select-metode-bayar');
    }
}
