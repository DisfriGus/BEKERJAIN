<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/registeruser', [App\Http\Controllers\AuthController::class, 'registeruser']);