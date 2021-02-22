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

Route::post('login', [AuthController::class, 'login']);

Route::post('user', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'index']);
    Route::patch('user', [UserController::class, 'update']);
    Route::get('users', [UserController::class, 'list']);
    Route::get('users/{id}', [UserController::class, 'show']);

    Route::get('posts', [PostController::class, 'index']);
    Route::post('post', [PostController::class, 'store']);
    Route::patch('post/{id}', [PostController::class, 'update']);
    Route::delete('post/{id}', [PostController::class, 'destroy']);
    Route::post('post/{id}/like-toggle', [PostController::class, 'like']);
    Route::get('post/{id}/liked', [PostController::class, 'liked']);

    Route::get('messages', [MessageController::class, 'index']);
    Route::get('message/{id}', [MessageController::class, 'show']);
    Route::post('message', [MessageController::class, 'store']);
    Route::patch('message/{id}', [MessageController::class, 'update']);
    Route::delete('message/{id}', [MessageController::class, 'destroy']);
    Route::post('message/{id}/view', [MessageController::class, 'view']);
});
