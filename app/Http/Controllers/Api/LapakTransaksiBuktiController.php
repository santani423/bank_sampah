<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiLapak;

class LapakTransaksiBuktiController extends Controller
{
    public function uploadBuktiTransfer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $transaksi = TransaksiLapak::find($id);
        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $path = $file->store('bukti_transfer', 'public');
            $transaksi->bukti_transfer = $path;
            $transaksi->type_pencairan = 'TRANSFER';
            $transaksi->approval = 'approved';
            $transaksi->save();
            return response()->json(['status' => true, 'message' => 'Bukti transfer berhasil diupload', 'path' => $path]);
        }
        return response()->json(['status' => false, 'message' => 'File tidak ditemukan'], 400);
    }
}
