<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CareerController as AdminCareerController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\Frontend\ComplaintController;

/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// News (Frontend)
Route::get('/terkini', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{id}', [NewsController::class, 'show'])->name('news.show');
Route::get('/kategori/{slug}', [NewsController::class, 'byCategory'])->name('news.category');

// Career (Frontend)
Route::get('/career', [CareerController::class, 'index'])->name('career.index');
Route::get('/career/{id}', [CareerController::class, 'show'])->name('career.show');

// Complaint (Frontend)
Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('complaint.index');
Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('complaint.store');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ========================================
    // DASHBOARD
    // ========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================================
    // CATEGORIES (Kategori Berita)
    // ========================================
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id_kategori}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id_kategori}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id_kategori}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // ========================================
    // NEWS (Berita)
    // ========================================
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id_berita}', [AdminNewsController::class, 'show'])->name('news.show');
    Route::get('/news/{id_berita}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id_berita}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id_berita}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // ========================================
    // COMMENTS (Komentar)
    // ========================================
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{id_komentar}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ========================================
    // COMPLAINTS (Pengaduan)
    // ========================================
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id_pengaduan}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{id_pengaduan}/approve', [AdminComplaintController::class, 'approve'])->name('complaints.approve');
    Route::delete('/complaints/{id_pengaduan}', [AdminComplaintController::class, 'destroy'])->name('complaints.destroy');

    // ========================================
    // CAREERS (Karir)
    // ========================================
    Route::get('/careers', [AdminCareerController::class, 'index'])->name('careers.index');
    Route::get('/careers/create', [AdminCareerController::class, 'create'])->name('careers.create');
    Route::post('/careers', [AdminCareerController::class, 'store'])->name('careers.store');
    Route::get('/careers/{id_karir}', [AdminCareerController::class, 'show'])->name('careers.show');
    Route::get('/careers/{id_karir}/edit', [AdminCareerController::class, 'edit'])->name('careers.edit');
    Route::put('/careers/{id_karir}', [AdminCareerController::class, 'update'])->name('careers.update');
    Route::delete('/careers/{id_karir}', [AdminCareerController::class, 'destroy'])->name('careers.destroy');


    // Profile Routes
Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update-password');
Route::post('/profile/upload-image', [App\Http\Controllers\Admin\ProfileController::class, 'uploadImage'])->name('profile.upload-image');


    Route::get('login', function () {
        return view('admin.login');
    })->name('admin.login');

    //notification
    // Notifications
    // Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])
    //     ->name('notifications.unread');
    // Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    //     ->name('notifications.read');
    // Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])
    //     ->name('notifications.markAllRead');

});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
