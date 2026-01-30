<?php

namespace App\View\Components\Select;

use App\Models\JenisMetodePenarikan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMetodeBayar extends Component
{
    public $metodeBayar,$required,$name,$value;

    public function __construct($metodeBayar = null, $required = true, $name = 'jenis_metode_penarikan_id', $value = null)
    {
        $this->metodeBayar = $metodeBayar ?? JenisMetodePenarikan::all();
        $this->required = $required;
        $this->name = $name;
        $this->value = $value;
    }

    public function render(): View|Closure|string
    {
        return view('components.select.select-metode-bayar');
    }
}
