<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\IsGuest;
use App\Http\Middleware\IsLogin;
use Illuminate\Support\Facades\Route;

Route::middleware(IsGuest::class)->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('registration', [AuthController::class, 'registration'])->name('register');
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');

    //login with google
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::middleware(IsLogin::class)->group(function () {
    Route::get('/dashboard', [TodoController::class, 'index']);
    Route::get('/session/{id}', [TodoController::class, 'show'])->name('projects.show');

    Route::post('/todo-sessions', [TodoController::class, 'storeSession'])->name('storeSession');
    Route::delete('/todo-sessions/{todoSession}', [TodoController::class, 'destroySession']);

    Route::post('/parent-lists/{sessionId}', [TodoController::class, 'storeParentList']);
    Route::delete('/parent-lists/{parentList}', [TodoController::class, 'destroyParentList']);

    Route::post('/cards/{parentId}', [TodoController::class, 'storeCard']);
    Route::delete('/cards/{card}', [TodoController::class, 'destroyCard']);

    Route::post('/checklists/{cardId}', [TodoController::class, 'storeChecklist']);
    Route::patch('/checklists/{checklist}', [TodoController::class, 'updateChecklist']);
    Route::delete('/checklists/{checklist}', [TodoController::class, 'destroyChecklist']);

    Route::get('/project/{TodoSession}', [TodoController::class, 'detail'])->name('project.detail');
    Route::post('/project/{TodoSession}/update', [TodoController::class, 'updateSession'])->name('project.update');
});
