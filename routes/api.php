<?php

use App\Http\Controllers\WorkRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth.api', 'json.response'])->group(function() {
    Route::prefix('v1')->group(function() {
        Route::get('/hello', function() { return 'Hello World'; });

        Route::post('work-requests/{id}/approve', [WorkRequestController::class, 'approve']);
        Route::apiResource('work-requests', WorkRequestController::class)->only('index', 'store', 'show');
    });
});

