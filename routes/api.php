<?php

use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::post('/proses-transaksi', [TransaksiController::class, 'prosesTransaksi']);
Route::post('/get-user-by-uid', [TransaksiController::class, 'getUserByUID']);
Route::post('/kurangi-saldo', [TransaksiController::class, 'kurangiSaldo']);
Route::get('/get-latest-uid', function () {
    $uid = \Cache::get('latestUID', null); // Mengambil UID dari cache
    return response()->json(['uid' => $uid]);
});