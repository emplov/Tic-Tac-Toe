<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group([
    'middleware' => ['auth']
], function () {
    Route::get('/', [FrontController::class, 'index']);

    Route::post('/rooms', [RoomController::class, 'store']);
    Route::post('/reset/{room_id}', [RoomController::class, 'reset']);
    Route::get('/room/{id}', [RoomController::class, 'game']);
    Route::post('/rooms/{id}/delete', [RoomController::class, 'delete']);
    Route::post('/room/{id}/join', [RoomController::class, 'join']);
    Route::post('/move/{block_id}/room/{room_id}', [RoomController::class, 'move']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::group([
    'middleware' => ['guest']
], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.login');

    Route::get('/sign-up', [AuthController::class, 'signUp'])->name('auth.register');
    Route::post('/sign-up', [AuthController::class, 'register'])->name('auth.register');
});
