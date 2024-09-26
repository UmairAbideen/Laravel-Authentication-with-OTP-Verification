<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Show the registration form
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Handle registration form submission
Route::post('register', [AuthController::class, 'register']);

// Show the OTP verification form
Route::get('verify', [AuthController::class, 'showOTPForm'])->name('verify');

// Handle OTP verification
Route::post('verify', [AuthController::class, 'verifyOTP'])->name('verify.otp');

// Show the login form
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('login', [AuthController::class, 'login']);

// Route for resending OTP
Route::post('resend-otp', [AuthController::class, 'resendOTP'])->name('resend.otp');

// Logout route
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard route
Route::get('dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
