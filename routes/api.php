<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JWTAuthenticate;
use Illuminate\Support\Facades\Route;

Route::post('login',[AuthController::class,'login']); 
Route::post('register',[AuthController::class,'register']); 
Route::post('logout',[AuthController::class,'logout']); 
