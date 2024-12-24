<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard', [DashboardController::class, 'findUser'])->name('findUser');
Route::post('/dashboard/deduct', [DashboardController::class, 'deductSaldo'])->name('deductSaldo');