<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (statistik) -- hanya user login & verifikasi
Route::get('/dashboard', [DocumentController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group routes untuk user yang sudah login & verifikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dokumen (lihat & download)
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('documents.download');

    // Group khusus admin
    Route::middleware([AdminMiddleware::class])->group(function () {
        // Dokumen CRUD (admin)
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Manajemen User (admin)
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/user/{id}/send-reset-password', [AdminUserController::class, 'sendResetPassword'])->name('admin.sendResetPassword');
        Route::post('/admin/users/{id}/make-admin', [AdminUserController::class, 'makeAdmin'])->name('admin.users.makeAdmin');
        Route::post('/admin/users/{id}/make-user', [AdminUserController::class, 'makeUser'])->name('admin.users.makeUser');
        Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy'); // <-- Tambah route hapus user
    });
});

// Logout (jaga-jaga jika tidak pakai Breeze)
use Illuminate\Support\Facades\Auth;
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Auth routes Breeze (login, register, forgot password, dll)

require __DIR__.'/auth.php';
