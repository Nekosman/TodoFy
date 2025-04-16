<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\SettingController;
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
    Route::get('/dashboard', [TodoController::class, 'index'])->name('dashboard');
    Route::get('/session/{id}', [TodoController::class, 'show'])->name('projects.show');

    Route::post('/todo-sessions', [TodoController::class, 'storeSession'])->name('storeSession');
    Route::delete('/todo-sessions/{todoSession}', [TodoController::class, 'destroySession']);

    Route::post('/parent-lists', [TodoController::class, 'storeParent'])->name('parent-lists.store');
    Route::get('/parent-lists/detail/{ParentList}', [TodoController::class, 'detailParent'])->name('parent-lists.detail');
    Route::get('/modal/parent-detail', function() {
        return view('components.modalDetailParent');
    })->name('modal.parent_detail');
    Route::put('/parent-lists/update/{ParentList}', [TodoController::class, 'updateParent'])->name('parent-lists.update');
    Route::delete('/parent-lists/delete/{ParentList}', [TodoController::class, 'deleteParent'])->name('parent-lists.delete');

    Route::post('/cards/create', [TodoController::class, 'storeCard'])->name('cards.store');
    Route::get('/modal/card-create-show', function () {
        return view('components.modalCreateCard');
    })->name('modal.createcard');
    Route::get('/cards/{card}', [TodoController::class, 'showCard'])->name('cards.show');
    Route::get('/modal/card-detail-show', function () {
        return view('components.modalDetailCard');
    })->name('modal.detailcard');
    Route::put('/cards/update/{card}', [TodoController::class, 'updateCard'])->name('cards.update');
    Route::delete('/cards/delete/{card}', [TodoController::class, 'deleteCard'])->name('cards.delete');

    Route::post('/checklists/{cardId}', [TodoController::class, 'storeChecklist']);
    Route::patch('/checklists/{checklist}', [TodoController::class, 'updateChecklist']);
    Route::delete('/checklists/{checklist}', [TodoController::class, 'destroyChecklist']);

    Route::get('/project/{TodoSession}', [TodoController::class, 'detail'])->name('project.detail');
    Route::put('/project/{TodoSession}/update', [TodoController::class, 'updateSession'])->name('project.update');
    Route::delete('/project/{session}/delete', [TodoController::class, 'deleteSession'])->name('project.delete');

    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/settings/updateProfile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/updatePassword', [SettingController::class, 'updateSecurity'])->name('settings.security.update');
});
