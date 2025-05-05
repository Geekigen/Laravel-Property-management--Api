<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeasesController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\TenantsController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'store']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});
Route::middleware(['auth:sanctum','role:admin,agent'])->group(function () {
    // property management
    Route::apiResource('/property', PropertyController::class);
});

Route::middleware(['auth:sanctum','role:admin,agent'])->group(function () {
    // property management
    Route::apiResource('/unit', UnitController::class);
});
Route::middleware(['auth:sanctum','role:admin,agent'])->group(function () {
    // property management
    Route::apiResource('/tenant', TenantsController::class);
    Route::apiResource('/lease', LeasesController::class);
    Route::apiResource('/payment', PaymentsController::class);
});
