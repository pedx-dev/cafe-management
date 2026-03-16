<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
// ...existing code...
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Authentication Routes
Auth::routes(['verify' => false, 'reset' => false]);

// Password Reset via Code
Route::get('/password/forgot', [PasswordResetCodeController::class, 'showRequestForm'])->name('password.request');
Route::post('/password/forgot', [PasswordResetCodeController::class, 'sendCode'])->name('password.email');
Route::get('/password/code', [PasswordResetCodeController::class, 'showCodeForm'])->name('password.code');
Route::post('/password/code', [PasswordResetCodeController::class, 'verifyCode'])->name('password.verify');
Route::get('/password/reset', [PasswordResetCodeController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetCodeController::class, 'resetPassword'])->name('password.update');

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Email Verification Code Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return redirect()->route('verification.code.show');
    })->name('verification.notice');
    Route::get('/verify-code', [VerificationCodeController::class, 'show'])->name('verification.code.show');
    Route::post('/verify-code', [VerificationCodeController::class, 'verify'])->name('verification.code.verify');
    Route::post('/verify-code/resend', [VerificationCodeController::class, 'resend'])->name('verification.code.resend');
});

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

// Authenticated User Routes (no email verification required)
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{item}', [CartController::class, 'add'])->name('cart.add');
        Route::put('/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/', [CartController::class, 'clear'])->name('cart.clear');
    });

    // ...existing code...
});

// Orders (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/place', [OrderController::class, 'placeOrder'])->name('orders.place');
        Route::get('/{id}/payment/success', [OrderController::class, 'paymentSuccess'])->name('orders.payment.success');
        Route::get('/{id}/payment/cancel', [OrderController::class, 'paymentCancel'])->name('orders.payment.cancel');
        Route::post('/{id}/payment/retry', [OrderController::class, 'retryCardPayment'])->name('orders.payment.retry');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/csv', [DashboardController::class, 'exportCsv'])->name('dashboard.export.csv');
    Route::get('/dashboard/export/word', [DashboardController::class, 'exportWord'])->name('dashboard.export.word');
    
    // Menu Management
    Route::resource('menu', AdminMenuController::class)->except(['show']);
    Route::put('menu/{menu}/availability', [AdminMenuController::class, 'updateAvailability'])->name('menu.availability');
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{id}/tracking', [AdminOrderController::class, 'updateTracking'])->name('updateTracking');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });
});