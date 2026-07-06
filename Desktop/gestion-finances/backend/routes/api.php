<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SavingsGoalController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

    Route::apiResource('categories', CategoryController::class)->except(['show']);
    Route::apiResource('transactions', TransactionController::class)->except(['show']);
    Route::apiResource('budgets', BudgetController::class)->except(['show']);

    Route::apiResource('savings-goals', SavingsGoalController::class)
        ->parameters(['savings-goals' => 'savingsGoal'])
        ->except(['show']);
    Route::post('/savings-goals/{savingsGoal}/contribute', [SavingsGoalController::class, 'contribute']);
});
