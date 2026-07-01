<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SpecimenController;

Route::get('/specimen/{id}/download/pdf', [SpecimenController::class, 'downloadPdf']);
Route::get('/specimen/{id}/download/zip', [SpecimenController::class, 'downloadZip']);


Route::post('/register', [AuthController::class, 'register_action']);

Route::post('/login', [AuthController::class, 'login_action']);

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

