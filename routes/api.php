<?php

use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('profiles')->group(function () {
    Route::get('/', [UserProfileController::class, 'index']);
    Route::post('/', [UserProfileController::class, 'store']);
    Route::get('/{id}', [UserProfileController::class, 'show']);
    Route::put('/{id}', [UserProfileController::class, 'update']);
    Route::delete('/{id}', [UserProfileController::class, 'destroy']);
    
    Route::get('/user/{userId}', [UserProfileController::class, 'getByUserId']);
    Route::patch('/{id}/status', [UserProfileController::class, 'updateStatus']);
});