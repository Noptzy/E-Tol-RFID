<?php

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