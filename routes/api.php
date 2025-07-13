<?php

use App\Http\Controllers\Api\countConttroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Petugas\TransaksiController as PetugasTransaksiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TessController;

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
 Route::get('/setting', [SettingController::class, 'index'])->name('api.setting');
 Route::post('/bayar', [TessController::class, 'createDanaDisbursement']);