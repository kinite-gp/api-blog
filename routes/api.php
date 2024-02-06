<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::prefix('post')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::put("/edit/{id}", [PostController::class, "edit_one"]);
        Route::post("/create", [PostController::class, "create_one"]);
    });
    Route::get("/{id}", [PostController::class, "show_one"]);
    Route::get("/all/{ordering}", [PostController::class, "post_list"]);
    Route::get("/all/{ordering}/{number}", [PostController::class, "post_take"]);
});

Route::prefix('comment')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::put("/edit/{id}", [CommentController::class, "edit_one"]);
        Route::post("/create", [CommentController::class, "create_one"]);
    });
});





