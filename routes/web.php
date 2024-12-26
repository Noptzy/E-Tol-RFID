<?php

<<<<<<< HEAD
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
=======
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/dashboard', [DashboardController::class, 'findUser'])->name('findUser');
Route::post('/dashboard/deduct', [DashboardController::class, 'deductSaldo'])->name('deductSaldo');
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
