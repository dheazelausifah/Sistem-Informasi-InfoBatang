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
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\Frontend\ComplaintController;


/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

// Home & Terkini
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/terkini', [NewsController::class, 'index'])->name('terkini');

// News Detail & Category
Route::get('/berita/{id}', [NewsController::class, 'show'])->name('berita.detail');
Route::get('/kategori/{id}', [NewsController::class, 'byCategory'])->name('berita.kategori');


// Career (Frontend)
Route::get('/karir/{id_karir}', [CareerController::class, 'detail'])->name('career.detail');
Route::get('/karir', [CareerController::class, 'index'])->name('karir.index');


// Complaint (Frontend)
Route::get('/pengaduan', [ComplaintController::class, 'index'])->name('pengaduan.index');
Route::post('/pengaduan', [ComplaintController::class, 'store'])->name('pengaduan.store');

//About
Route::get('/tentang', function () {return view('frontend.about.index');})->name('tentang');

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Login & Register (Guest)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('register.submit');

    // Protected Routes
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id_kategori}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id_kategori}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id_kategori}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id_berita}', [AdminNewsController::class, 'show'])->name('news.show');
    Route::get('/news/{id_berita}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id_berita}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id_berita}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // Comments
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{id_komentar}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Complaints
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{id_pengaduan}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{id_pengaduan}/approve', [AdminComplaintController::class, 'approve'])->name('complaints.approve');
    Route::delete('/complaints/{id_pengaduan}', [AdminComplaintController::class, 'destroy'])->name('complaints.destroy');

    // Careers
    Route::get('/careers', [AdminCareerController::class, 'index'])->name('careers.index');
    Route::get('/careers/create', [AdminCareerController::class, 'create'])->name('careers.create');
    Route::post('/careers', [AdminCareerController::class, 'store'])->name('careers.store');
    Route::get('/careers/{id_karir}', [AdminCareerController::class, 'show'])->name('careers.show');
    Route::get('/careers/{id_karir}/edit', [AdminCareerController::class, 'edit'])->name('careers.edit');
    Route::put('/careers/{id_karir}', [AdminCareerController::class, 'update'])->name('careers.update');
    Route::delete('/careers/{id_karir}', [AdminCareerController::class, 'destroy'])->name('careers.destroy');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/upload-image', [App\Http\Controllers\Admin\ProfileController::class, 'uploadImage'])->name('profile.upload-image');
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
    return redirect('/');
})->name('logout');
