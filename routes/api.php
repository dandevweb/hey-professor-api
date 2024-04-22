<?php

use App\Http\Controllers\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', function () {
    return User::all();
});

// region Authenticated
Route::middleware('auth:sanctum')->group(function () {

    // region Question
    Route::post('questions', Question\StoreController::class)->name('questions.store');

    // endregion
});
// endregion
