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
        $data->description = json_encode($request->all());
        $data->save();

        $transak = PencairanLapak::where('kode_pencairan', $request->transaction->external_id)->first();
        // if ($transak) {
        //     $transak->status = $request->transaction->status;
        //     $transak->save();
        // }
        

        return response()->json([
            'message' => 'Tes API works!',
            'data' => $request->transaction,
        ]);
    }
}
