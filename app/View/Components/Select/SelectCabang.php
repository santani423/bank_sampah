<?php

namespace App\View\Components\Select;

use App\Models\Cabang;
use App\Models\Petugas;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCabang extends Component
{
     
    public $cabang, $name;
    public function __construct($name = 'cabang')
    {
        //
        $authUser = auth()->user();

        // Cari petugas berdasarkan email user login
        $petugas = Petugas::where('email', $authUser->email)->first();

        

        $query = Cabang::query()
            ->join('petugas_cabangs', 'cabangs.id', '=', 'petugas_cabangs.cabang_id')
            ->join('petugas', 'petugas_cabangs.petugas_id', '=', 'petugas.id')
            ->select('cabangs.*')
            ->distinct();

        // Jika petugas ditemukan, filter berdasarkan petugas
        if ($petugas) {
            $query->where('petugas.id', $petugas->id);
        }

        $cabang = $query->get();

        $this->cabang = $cabang;
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
