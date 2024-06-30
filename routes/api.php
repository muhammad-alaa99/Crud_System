<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\NoteController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('v1/')->group(function (Router $route) {
    $route->get('logout', [AuthController::class, 'logout']);
    $route->post('update-profile', [AuthController::class, 'updateProfile']);

    Route::prefix('/notes')->group(function (Router $route) {
        $route->get('/', [NoteController::class, 'index']);
        $route->post('/', [NoteController::class, 'create']);
        $route->post('/{note_id}', [NoteController::class, 'update']);
        $route->get('/{note_id}', [NoteController::class, 'show']);
        $route->delete('/{note_id}', [NoteController::class, 'delete']);
    });
});


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully']);
})->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification email sent']);
})->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
