<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'unknownUser'], function(){
    // Sign In, Login
    Route::get('/', [AuthenticationController::class,'signInForm'])->name('signInForm');
    Route::get('/sign-in', [AuthenticationController::class,'signInForm'])->name('signInForm');
    Route::get('/login', [AuthenticationController::class,'signInForm'])->name('signInForm');

    Route::post('/sign-in', [AuthenticationController::class,'signIn'])->name('logIn');
    Route::post('/login', [AuthenticationController::class,'signIn'])->name('logIn');

    // Sign Up, Registration
    Route::get('/sign-up', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
    Route::get('/register', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
    Route::get('/registration', [AuthenticationController::class,'signUpForm'])->name('signUpForm');
	Route::post('/registered', [AuthenticationController::class,'signUpStore'])->name('signUp');


    // Forgot Password
    Route::get('/forgot', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-pass', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');
    Route::get('/forgot-password', [AuthenticationController::class,'forgotForm'])->name('forgotPasswordForm');

    Route::post('/forgot-password', [AuthenticationController::class,'forgotPassword'])->name('forgotPassword');

    // Reset Password
    Route::get('/reset-password', [AuthenticationController::class,'resetPasswordForm'])->name('resetPasswordForm');
    Route::post('/reset-password', [AuthenticationController::class,'resetPassword'])->name('resetPassword');
});
Route::get('/sign-out', [AuthenticationController::class,'signOut'])->name('logOut');
Route::get('/logout', [AuthenticationController::class,'signOut'])->name('logOut');

// Super Admin
Route::group(['middleware' => 'isSuperAdmin'], function(){
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class,'index'])->name('superadminDashboard');
		
		Route::get('/profile', [UserController::class,'userProfile'])->name('superadmin.userProfile');
		Route::post('/profile', [UserController::class,'storeProfile'])->name('superadmin.storeProfile');
		Route::post('/changePass', [UserController::class,'changePass'])->name('superadmin.changePass');
		Route::post('/changePer', [UserController::class,'changePer'])->name('superadmin.changePer');
		Route::post('/changeAcc', [UserController::class,'changeAcc'])->name('superadmin.changeAcc');
	});
});
