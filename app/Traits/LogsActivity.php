<?php

namespace App\Traits;

use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        // Simpan data sebelum update/delete
        static::updating(function ($model) {
            $model->temp_before_data = $model->getOriginal();
        });

        static::deleting(function ($model) {
            $model->temp_before_data = $model->getOriginal();
        });

        // Logging saat create
        static::created(function ($model) {
            Logging::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'action' => 'create_' . strtolower(class_basename($model)),
                'description' => 'Membuat ' . class_basename($model),
                'data_before' => null,
                'data_after' => $model->toArray(),
                'ip_address' => request()->ip() ?? null,
                'user_agent' => request()->userAgent() ?? null,
            ]);
        });

        // Logging saat update
        static::updated(function ($model) {
            Logging::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'action' => 'update_' . strtolower(class_basename($model)),
                'description' => 'Mengupdate ' . class_basename($model),
                'data_before' => $model->temp_before_data ?? null,
                'data_after' => $model->getChanges(),
                'ip_address' => request()->ip() ?? null,
                'user_agent' => request()->userAgent() ?? null,
            ]);
        });

        // Logging saat delete
        static::deleted(function ($model) {
            Logging::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'action' => 'delete_' . strtolower(class_basename($model)),
                'description' => 'Menghapus ' . class_basename($model),
                'data_before' => $model->temp_before_data ?? null,
                'data_after' => null,
                'ip_address' => request()->ip() ?? null,
                'user_agent' => request()->userAgent() ?? null,
            ]);
        });

        // Opsional: logging saat view/ambil data
        // static::retrieved(function ($model) {
        //     Logging::create([
        //         'user_id' => Auth::check() ? Auth::id() : null,
        //         'action' => 'view_' . strtolower(class_basename($model)),
        //         'description' => 'Mengambil data ' . class_basename($model),
        //         'data_before' => null,
        //         'data_after' => $model->toArray(),
        //         'ip_address' => request()->ip() ?? null,
        //         'user_agent' => request()->userAgent() ?? null,
        //     ]);
        // });
    }
}
