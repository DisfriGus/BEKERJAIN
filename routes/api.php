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
Route::get('pt/{id}', [AuthController::class, 'getPerusahaan']);

// User Functions
Route::put('user/edit/{id}', [UserController::class, 'editUser']);

Route::get('user/experience/{id}', [UserController::class, 'checkUserExperience']);
Route::get('user/lowongan', [UserController::class, 'getAllLowongan']);
Route::get('user/perusahaan', [UserController::class, 'getAllPerusahaan']);
Route::post('user/apply/{lowonganId}/{id}', [UserController::class, 'applyLowongan']);
Route::get('user/lowongan/{id}', [UserController::class, 'getLowonganInfo']);

// Perusahaan Functions
Route::get('pt/allperusahaan', [PerusahaanController::class, 'getAllPerusahaan']);

Route::post('pt/newlowongan/{id}', [PerusahaanController::class, 'createLowongan']);
Route::put('pt/lowonganperusahaan/edit/{id}', [UserController::class, 'editLowongan']);
Route::get('pt/lowonganperusahaan/{id}', [PerusahaanController::class, 'checkPerusahaanLowongan']);
Route::get('pt/lowonganperusahaan/pendaftar/{id}', [PerusahaanController::class, 'checkPerusahaanLowongan']);