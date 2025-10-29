<?php

use App\Http\Controllers\Api\countConttroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Petugas\TransaksiController as PetugasTransaksiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TessController;
use App\Http\Controllers\Api\TimeApiController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\Api\CleanController;
use App\Http\Controllers\Api\UserFaceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/callback', [PetugasTransaksiController::class, 'callback']);

Route::get('/summary/counts', [countConttroller::class, 'counts'])->name('api.summary.counts');
Route::get('/settings', [SettingController::class, 'index'])->name('api.settings');
Route::post('/bayar', [TessController::class, 'createDanaDisbursement']);


Route::apiResource('cleans', CleanController::class);


Route::apiResource('activities', ActivityController::class);




Route::post('/api/user-face/create', [UserFaceController::class, 'create'])
    ->name('api.user-face.create')
    ->middleware('throttle:6000,1'); // batasi max 60 request per menit





Route::prefix('teams')->group(function () {
    Route::get('/', [TimeApiController::class, 'index']);       // GET semua data
    Route::get('/{id}', [TimeApiController::class, 'show']);    // GET satu data berdasarkan id
    Route::post('/', [TimeApiController::class, 'store']);      // POST tambah data
    Route::post('/{id}', [TimeApiController::class, 'update']); // POST update data (bisa juga pakai PUT)
    Route::delete('/{id}', [TimeApiController::class, 'destroy']); // DELETE hapus data
});
