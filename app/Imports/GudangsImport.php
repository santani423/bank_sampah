<?php

namespace App\Imports;

use App\Models\Gudang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GudangsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        return new Gudang([
            'kode_gudang' => $row['kode_gudang'],
            'nama_gudang' => $row['nama_gudang'],
            'alamat' => $row['alamat'],
            'kota' => $row['kota'],
            'provinsi' => $row['provinsi'],
            'kode_pos' => $row['kode_pos'],
            'telepon' => $row['telepon'],
            'status' => $row['status'] ?? 'aktif',
        ]);
    }
}
//  php artisan make:import GudangsImport --model=Gudang
