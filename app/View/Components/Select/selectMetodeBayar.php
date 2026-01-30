<?php

namespace App\View\Components\Select;

use App\Models\JenisMetodePenarikan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMetodeBayar extends Component
{
    public $metodeBayar,$required,$name;

    public function __construct($metodeBayar = null, $required = true, $name = 'jenis_metode_penarikan_id')
    {
        $this->metodeBayar = $metodeBayar ?? JenisMetodePenarikan::all();
        $this->required = $required;
        $this->name = $name;
    }

    public function render(): View|Closure|string
    {
        return view('components.select.select-metode-bayar');
    }
}
