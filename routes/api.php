<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthorizationController;
use App\Http\Controllers\Api\CommentController;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/store', [PostController::class, 'store']);
    Route::put('/update/{id}', [PostController::class, 'update']);
    Route::delete('/delete/{id}', [PostController::class, 'destroy']);

    Route::post('/comment', [CommentController::class, 'store']);
    Route::put('/comment/{id}', [CommentController::class, 'update']);

    Route::get('/logout', [AuthorizationController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthorizationController::class, 'me'])->middleware('auth:sanctum');
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::post('/login', [AuthorizationController::class, 'login']);

