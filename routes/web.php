<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify-mfa', [AuthController::class, 'showMfaForm'])->name('verify-mfa');
Route::post('/verify-mfa', [AuthController::class, 'verifyMfa']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.dashboard');
    })->name('dashboard');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('web')->group(function () {
    // MFA Routes
    Route::get('/verify-mfa', [AuthController::class, 'showMfaForm'])->name('verify-mfa');
    Route::post('/verify-mfa', [AuthController::class, 'verifyMfa']);
    
    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        //Route::resource('customers', CustomerController::class);
    });

Route::resource('customers', CustomerController::class);

});
