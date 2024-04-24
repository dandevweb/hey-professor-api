<?php

use App\Http\Controllers\{Auth, Question};
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('users', function () {
    return User::all();
});

// region Auth
Route::middleware(['guest', 'web'])->group(function () {
    Route::post('login', Auth\LoginController::class)->name('login');
    Route::post('register', Auth\RegisterController::class)->name('register');
});
// region Auth

// region Authenticated
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // region Question
    Route::post('questions', Question\StoreController::class)->name('questions.store');
    Route::put('questions/{question}', Question\UpdateController::class)->name('questions.update');
    Route::delete('questions/{question}', Question\DeleteController::class)->name('questions.delete');
    Route::patch('questions/{question}/archive', Question\ArchiveController::class)->name('questions.archive');
    Route::put('questions/{id}/restore', Question\RestoreController::class)->name('questions.restore');
    Route::put('questions/{question}/publish', Question\PublishController::class)->name('questions.publish');
    // endregion
});
// endregion
