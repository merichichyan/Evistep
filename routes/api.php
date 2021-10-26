<?php

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

/*
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

*/


Route::post('login', [\App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::resource('post', App\Http\Controllers\PostController::class);
Route::resource('post.comment', App\Http\Controllers\CommentController::class);
