<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\RequestAssignmentController;
use App\Http\Controllers\FeedbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum-protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users (admin/self)
    Route::apiResource('users', UserController::class)->except(['store']);

    // Service Requests
    Route::apiResource('service-requests', ServiceRequestController::class);

    // Request Assignments
    Route::get('/assignments', [RequestAssignmentController::class, 'index']);
    Route::post('/assignments', [RequestAssignmentController::class, 'store']);
    Route::post('/assignments/{assignment}/complete', [RequestAssignmentController::class, 'complete']);

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'index']);
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::put('/feedbacks/{id}', [FeedbackController::class, 'update']);
});