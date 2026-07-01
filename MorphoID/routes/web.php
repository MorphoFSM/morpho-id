<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SpecimenController;
use App\Http\Controllers\ProfileController;

// ==========================================
// PUBLIC ROUTES (No Auth Required)
// ==========================================
Route::get('/', function () { return view('public.intro'); })->name('home');

Route::middleware(['auth:admin,web'])->group(function () {
    // Core Catalog Routes (Protected)
    Route::get('/index', [SpecimenController::class, 'index'])->name('index');
    Route::get('/explore/{id?}', [SpecimenController::class, 'explore'])->name('explore');
    Route::get('/specimen/{id}', [SpecimenController::class, 'show'])->name('specimen.show');
    Route::get('/specimen/{id}/download/pdf', [SpecimenController::class, 'downloadPdf'])->name('specimen.download.pdf');
    Route::get('/specimen/{id}/download/zip', [SpecimenController::class, 'downloadZip'])->name('specimen.download.zip');
    Route::get('/search', [SpecimenController::class, 'search'])->name('search');
    Route::get('/compare', [SpecimenController::class, 'compare'])->name('specimen.compare');
});

Route::get('/intro', function () { return view('public.intro'); })->name('intro');
Route::get('/verify-email/{id}/{hash}/{role}', [AuthController::class, 'verify_email'])->name('verify.email');

// ==========================================
// GUEST ROUTES (Auth)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login_action']);
    
    Route::get('/registration', [AuthController::class, 'showRegistrationForm'])->name('registration');
    Route::post('/registration', [AuthController::class, 'register_action']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// AUTHENTICATED USER ROUTES
// ==========================================
Route::middleware(['auth:admin,web'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/role-request', [\App\Http\Controllers\Admin\RoleRequestController::class, 'store'])->name('role.request');

    // Comments
    Route::post('/specimen/{id}/comment', [SpecimenController::class, 'storeComment'])->name('comment.store');
    Route::put('/comment/{id}', [SpecimenController::class, 'updateComment'])->name('comment.update');
    Route::delete('/comment/{id}', [SpecimenController::class, 'destroyComment'])->name('comment.destroy');
    Route::post('/comment/{id}/like', [SpecimenController::class, 'likeComment'])->name('comment.like');
});

// ==========================================
// ADMIN DASHBOARD ROUTES
// ==========================================
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Exports
    Route::get('/', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'admin'])->name('dashboard'); // was 'admin'
    Route::get('/export', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'exportExcel'])->name('export');
    
    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AdminAuditController::class, 'index'])->name('audit');
    
    // Visitor Logs
    Route::get('/visits', [\App\Http\Controllers\Admin\VisitController::class, 'index'])->name('visits');
    Route::get('/visits/export', [\App\Http\Controllers\Admin\VisitController::class, 'exportExcel'])->name('visits.export');
    Route::get('/visits/chart-data', [\App\Http\Controllers\Admin\VisitController::class, 'chartData'])->name('visits.chart');
    Route::post('/visits/bulk-delete', [\App\Http\Controllers\Admin\VisitController::class, 'bulkDelete'])->name('visits.bulkDelete');

    // Role Requests
    Route::get('/role-requests', [\App\Http\Controllers\Admin\RoleRequestController::class, 'index'])->name('role_requests');
    Route::post('/role-requests/{id}/approve', [\App\Http\Controllers\Admin\RoleRequestController::class, 'approve'])->name('role_requests.approve');
    Route::post('/role-requests/{id}/reject', [\App\Http\Controllers\Admin\RoleRequestController::class, 'reject'])->name('role_requests.reject');
    Route::post('/role-requests/{id}/approve-pending', [\App\Http\Controllers\Admin\RoleRequestController::class, 'approvePendingAdmin'])->name('admin.role_requests.approve_pending');
    Route::post('/role-requests/{id}/reject-pending', [\App\Http\Controllers\Admin\RoleRequestController::class, 'rejectPendingAdmin'])->name('admin.role_requests.reject_pending');
    
    // Specimen Management (CRUD)
    Route::post('/specimen/simpan', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'store'])->name('specimen.store');
    Route::get('/specimen/edit/{id}', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'edit']);
    Route::match(['post', 'put'], '/specimen/update/{id}', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'update']);
    Route::get('/specimen/update/{id}', function ($id) { return redirect('/admin/specimen/edit/' . $id); });
    Route::delete('/specimen/padam/{id}', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'destroy']);
    
    // Category Management (CRUD)
    Route::post('/kategori/simpan', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'storeCategory'])->name('kategori.store');
    Route::post('/kategori/edit/{id}', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'updateCategory']);
    Route::delete('/kategori/padam/{id}', [\App\Http\Controllers\Admin\AdminSpecimenController::class, 'destroyCategory']);
});

// Diagnostics
Route::get('/test-limit', function () { return ini_get('upload_max_filesize'); });
Route::get('/test-post-limit', function () { return ini_get('post_max_size'); });
Route::get('/test-db', function () { return env('DB_CONNECTION'); });
