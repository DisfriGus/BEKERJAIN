<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PerusahaanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication
Route::post('registeruser', [AuthController::class, 'registerUser']);
Route::post('loginuser', [AuthController::class, 'loginUser']);
Route::get('user/{id}', [AuthController::class, 'getUser']);

Route::post('registerperusahaan', [AuthController::class, 'registerPerusahaan']);
Route::post('loginperusahaan', [AuthController::class, 'loginPerusahaan']);

// User Functions
Route::put('user/edit/{id}', [UserController::class, 'editUserDescription']);

// Perusahaan Functions

Route::get('allperusahaan', [PerusahaanController::class, 'getAllPerusahaan']);
Route::post('newlowongan/{id}', [PerusahaanController::class, 'createLowongan']);
Route::get('lowonganperusahaan/{id}', [PerusahaanController::class, 'checkPerusahaanLowongan']);
