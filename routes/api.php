<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarsController;

// Protecting the logout route with auth:sanctum middleware
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Routes for registration and login (open to all users)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// API resource for 'cars'
Route::apiResource('cars', CarsController::class);
