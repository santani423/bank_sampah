<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PencairanLapak;
use App\Models\Tess;
use Illuminate\Http\Request;

class TesController extends Controller
{
    public function disbursementSend(Request $request)
    {
        $data = new Tess();
        $data->name = 'disbursementSend';
        $data->description = json_encode($request->transaction ?? 'No external_id');
        $data->save();
        $external_id = $request->transaction['external_id'] ?? '';

        $pencairanLapak = PencairanLapak::with('lapak.jenisMetodePenarikan')->where('kode_pencairan', $external_id)->first();

        if ($pencairanLapak) {
           if($pencairanLapak->sumber_dana == 'saldo_admin'){
               
                
           }
        }   

        return response()->json([
            'message' => 'Tes API works!',
            'data' => $pencairanLapak,
            'transaction' => $request->transaction ?? 'No transaction data',
        ]);
    }
}
