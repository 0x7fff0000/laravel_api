<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Models\Message;

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

Route::post('/auth', [AuthController::class, 'auth']);

Route::post('/user', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::patch('/', [UserController::class, 'update']);
    });

    Route::get('/users', [UserController::class, 'list']);

    Route::get('posts', [PostController::class, 'index']);

    Route::prefix('post')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::patch('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
        Route::post('/{id}/like-toggle', [PostController::class, 'like']);
        Route::get('/{id}/liked', [PostController::class, 'liked']);
    });

    Route::get('messages', [MessageController::class, 'index']);

    Route::prefix('message')->group(function () {
        Route::get('/{id}', [MessageController::class, 'show']);
        Route::post('', [MessageController::class, 'store']);
        Route::patch('/{id}', [MessageController::class, 'update']);
        Route::delete('/{id}', [MessageController::class, 'destroy']);
        Route::post('/{id}/view', [MessageController::class, 'view']);
    });
});
