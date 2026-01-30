<?php

namespace App\View\Components\Select;

use App\Models\JenisMetodePenarikan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMetodeBayar extends Component
{
    public $metodeBayar,$required;

    public function __construct($metodeBayar = null, $required = true)
    {
        $this->metodeBayar = $metodeBayar ?? JenisMetodePenarikan::all();
        $this->required = $required;
    }

    public function render(): View|Closure|string
    {
        return view('components.select.select-metode-bayar');
    }
}
