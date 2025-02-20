<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/register",[AuthController::class,"register"]);
Route::post("/login",[AuthController::class,"login"]);
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/logout",[AuthController::class,"logout"]);
    Route::post("/post/create",[PostController::class,"create"]);
    Route::put("/post/edit/{post_id}",[PostController::class,"edit"]);
    Route::delete("/post/delete/{post_id}",[PostController::class,"destroy"]);
});
Route::get("/post",[PostController::class,"getPosts"]);
Route::get("/post/{post_id}",[PostController::class,"getPost"]);