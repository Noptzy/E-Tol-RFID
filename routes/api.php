<?php

<<<<<<< HEAD
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::post('/proses-transaksi', [TransaksiController::class, 'prosesTransaksi']);
Route::post('/get-user-by-uid', [TransaksiController::class, 'getUserByUID']);
Route::post('/kurangi-saldo', [TransaksiController::class, 'kurangiSaldo']);
Route::get('/get-latest-uid', function () {
    $uid = \Cache::get('latestUID', null); // Mengambil UID dari cache
    return response()->json(['uid' => $uid]);
});
=======
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/proses-transaksi', [TransaksiController::class, 'prosesTransaksi']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/api/find-user', [DashboardController::class, 'findUser'])->name('api.findUser');
Route::post('/api/deduct-saldo', [DashboardController::class, 'deductSaldo'])->name('api.deductSaldo');
Route::post('/api/find-user', [DashboardController::class, 'findUserAjax'])->name('api.findUserAjax');
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
