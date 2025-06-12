<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (statistik)
Route::get('/dashboard', [DocumentController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group routes yang butuh login
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Kalau ada fitur update foto:
    // Route::post('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile.photo.update');

    // Dokumen (lihat & download)
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');

    // Hanya admin
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });
});

// Logout (Breeze handle di routes/auth.php, tapi ini jaga-jaga jika perlu custom)
use Illuminate\Support\Facades\Auth;
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Auth routes Breeze (login, forgot password, dsb)
require __DIR__.'/auth.php';
