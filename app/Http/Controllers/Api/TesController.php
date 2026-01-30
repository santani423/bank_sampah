<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tess;
use Illuminate\Http\Request;

class TesController extends Controller
{
    public function disbursementSend(Request $request)
    {
        $data = new Tess();
        $data->name = 'disbursementSend';
        $data->description = json_encode($request->transaction);
        $data->save();

        return response()->json([
            'message' => 'Tes API works!',
            'data' => $data,
        ]);
    }
}
