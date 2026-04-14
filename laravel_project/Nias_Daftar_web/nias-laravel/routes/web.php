<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NiasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('auth.login.show'));

Route::get('/login',    [AuthController::class, 'showLogin'])->name('auth.login.show');
Route::post('/login',   [AuthController::class, 'login'])->name('auth.login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register.show');
Route::post('/register',[AuthController::class, 'register'])->name('auth.register');
Route::post('/logout',  [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| NIAS Routes — semua wajib login (middleware di konstruktor controller)
|--------------------------------------------------------------------------
*/
Route::resource('nias', NiasController::class);
