<?php

namespace App\Imports;

use App\Models\Sampah;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SampahImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validate row data (optional, can be improved)
        $validator = Validator::make($row, [
            'kode_sampah' => 'required|string|max:50',
            'nama_sampah' => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            // Skip invalid row
            return null;
        }

        return new Sampah([
            'kode_sampah' => $row['kode_sampah'],
            'nama_sampah' => $row['nama_sampah'],
            'harga_per_kg' => $row['harga_per_kg'],
        ]);
    }
}
