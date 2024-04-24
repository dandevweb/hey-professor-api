<?php

use App\Http\Controllers\{Auth};
use Illuminate\Support\Facades\Route;

// region Auth
Route::post('login', Auth\LoginController::class)->name('login');
Route::post('register', Auth\RegisterController::class)->name('register');
// region Auth

Route::post('logout', Auth\LogoutController::class)->middleware(['auth'])->name('logout');
