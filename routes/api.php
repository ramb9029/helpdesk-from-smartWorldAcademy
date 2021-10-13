<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('user')->name('user.')->group(function () {
    Route::post('/login', [\App\Http\Controllers\User\LoginController::class, 'login']);

    Route::middleware('moderator')->group(function () {
        Route::post('/registration', [\App\Http\Controllers\User\UserController::class, 'registration']);
        Route::middleware('userCheckId')->group(function () {
            Route::get('/users', [\App\Http\Controllers\User\UserInfoController::class, 'getUsers']);
        });
    });

    Route::middleware('userCheckId')->group(function () {
        Route::patch('/update/{id}', [\App\Http\Controllers\User\UserController::class, 'update'])->name('updateUser');
        Route::get('/{id}', [\App\Http\Controllers\User\UserInfoController::class, 'getUser']);
        Route::patch("/delete/{id}", [\App\Http\Controllers\User\UserController::class, 'delete'])->name('deleteUser');
        Route::delete('/logout', [\App\Http\Controllers\User\LoginController::class, 'logout']);
        Route::delete('/logoutAllDevice', [\App\Http\Controllers\User\UserController::class, 'logoutAllDevice']);
        Route::delete('/logoutDevice/{id}', [\App\Http\Controllers\User\LoginController::class, 'logoutDevice']);
    });
});

Route::middleware('userCheckId')->group(function () {
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/get/', [OrderController::class, 'getOne'])->name('getOne');
        Route::post('create', [OrderController::class, 'create'])->name('create');
        Route::get('delete/{id}', [OrderController::class, 'delete'])->name('delete');
        Route::patch('update/{id}', [OrderController::class, 'update'])->name('update');
        Route::get('', [OrderController::class, 'getAll'])->name('getAll');
    });
    Route::prefix('review')->name('review.')->group(function () {
        Route::post('create', [ReviewController::class, 'create'])->name('create');
    });
});

Route::get('/order/guest', [OrderController::class, 'getAll'])->name('getAllGuest');
