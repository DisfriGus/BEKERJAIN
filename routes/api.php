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
Route::delete('deleteuser/{id}', [AuthController::class, 'deleteUser']);

Route::post('registerperusahaan', [AuthController::class, 'registerPerusahaan']);
Route::post('loginperusahaan', [AuthController::class, 'loginPerusahaan']);
Route::get('pt/{id}', [AuthController::class, 'getPerusahaan']);

// User Functions
Route::put('user/edit/{id}', [UserController::class, 'editUser']);
Route::get('user/lowonganstatus/{id}', [UserController::class, 'checkUserLowonganStatus']);
Route::get('user/experience/{id}', [UserController::class, 'checkUserExperience']);
Route::get('user/lowongan/all', [UserController::class, 'getAllLowongan']);
Route::get('user/perusahaan/all', [UserController::class, 'getAllPerusahaan']);
Route::post('user/apply', [UserController::class, 'applyLowongan']);
Route::get('user/lowongan/{id}', [UserController::class, 'getLowonganInfo']);
Route::get('user/allkerja/get', [UserController::class, 'getAllKerja']);
Route::post('user/editpfp/{id}', [UserController::class, 'editUserPFP']);

// Perusahaan Functions
Route::get('pt/allperusahaan', [PerusahaanController::class, 'getAllPerusahaan']);
Route::post('pt/newlowongan/{id}', [PerusahaanController::class, 'createLowongan']);
Route::put('pt/lowonganperusahaan/edit/{id}', [PerusahaanController::class, 'editLowongan']);
Route::get('pt/lowonganperusahaan/{id}', [PerusahaanController::class, 'checkPerusahaanLowongan']);
Route::get('pt/lowonganperusahaan/pendaftar/{id}', [PerusahaanController::class, 'checkPendaftarLowongan']);
Route::get('pt/lowonganperusahaan/pegawai/{id}', [PerusahaanController::class, 'checkPegawai']);
Route::put('pt/lowonganperusahaan/terima/{idLowongan}/{idUser}', [PerusahaanController::class, 'terimaPendaftarLowongan']);
Route::put('pt/lowonganperusahaan/tolak/{idLowongan}/{idUser}', [PerusahaanController::class, 'tolakPendaftarLowongan']);
Route::put('pt/lowonganperusahaan/selesai/{idLowongan}/{idUser}', [PerusahaanController::class, 'selesaikanPekerjaLowongan']);