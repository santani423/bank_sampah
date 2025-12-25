<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Simpan gambar ke folder berdasarkan tanggal (Y/m/d)
     *
     * @param UploadedFile $file
     * @param string $basePath
     * @return string path file yang disimpan
     */
    public static function storeImageByDate(UploadedFile $file, string $basePath = 'images'): string
    {
        $datePath = date('Y/m/d');

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        return $file->storeAs(
            "{$basePath}/{$datePath}",
            $filename,
            'public'
        );
    }
}
