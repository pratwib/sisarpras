<?php

use App\Http\Controllers\BorrowController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');

    // Login routes
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Register routes
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        if (Auth::user()->role == 'user') {
            return redirect('/user/dashboard');
        } elseif (Auth::user()->role == 'admin') {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/superadmin/dashboard');
        }
    });

    // Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('UserAccess:superadmin')->name('dashboard');

        Route::get('/profile', [UserController::class, 'showProfile'])->middleware('UserAccess:superadmin')->name('profile.superadmin');
        Route::post('/profile/{id}/edit', [UserController::class, 'updateProfile'])->middleware('UserAccess:superadmin')->name('profile.edit.superadmin');

        Route::get('/location', [LocationController::class, 'show'])->middleware('UserAccess:superadmin')->name('location');
        Route::post('/location/add', [LocationController::class, 'create'])->middleware('UserAccess:superadmin')->name('location.add');
        Route::post('/location/{id}/edit', [LocationController::class, 'update'])->middleware('UserAccess:superadmin')->name('location.edit');
        Route::delete('/location/{id}/delete', [LocationController::class, 'delete'])->middleware('UserAccess:superadmin')->name('location.delete');

        Route::get('/admin', [UserController::class, 'showAdmin'])->middleware('UserAccess:superadmin')->name('admin');
        Route::post('/admin/add', [UserController::class, 'createAdmin'])->middleware('UserAccess:superadmin')->name('admin.add');
        Route::post('/admin/{id}/edit', [UserController::class, 'updateAdmin'])->middleware('UserAccess:superadmin')->name('admin.edit');
        Route::delete('/admin/{id}/delete', [UserController::class, 'deleteAdmin'])->middleware('UserAccess:superadmin')->name('admin.delete');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('UserAccess:admin')->name('dashboard');

        Route::get('/profile', [UserController::class, 'showProfile'])->middleware('UserAccess:admin')->name('profile.admin');
        Route::post('/profile/{id}/edit', [UserController::class, 'updateProfile'])->middleware('UserAccess:admin')->name('profile.edit.admin');

        Route::get('/item', [ItemController::class, 'showByLocation'])->middleware('UserAccess:admin')->name('item.admin');
        Route::post('/item/add', [ItemController::class, 'create'])->middleware('UserAccess:admin')->name('item.add');
        Route::post('/item/{id}/edit', [ItemController::class, 'update'])->middleware('UserAccess:admin')->name('item.edit');
        Route::delete('/item/{id}/delete', [ItemController::class, 'delete'])->middleware('UserAccess:admin')->name('item.delete');

        Route::get('/borrow', [BorrowController::class, 'showAdmin'])->middleware('UserAccess:admin')->name('borrow.admin');
        Route::post('/borrow/{id}/approve', [BorrowController::class, 'approved'])->middleware('UserAccess:admin')->name('borrow.approve');
        Route::post('/borrow/{id}/decline', [BorrowController::class, 'declined'])->middleware('UserAccess:admin')->name('borrow.decline');
        Route::post('/borrow/{id}/borrow', [BorrowController::class, 'borrowed'])->middleware('UserAccess:admin')->name('borrow.borrow');
        Route::post('/borrow/{id}/return', [BorrowController::class, 'returned'])->middleware('UserAccess:admin')->name('borrow.return');

        Route::get('/history', [BorrowController::class, 'historyAdmin'])->middleware('UserAccess:admin')->name('history.admin');
    });

    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('UserAccess:user')->name('dashboard');

        Route::get('/profile', [UserController::class, 'showProfile'])->middleware('UserAccess:user')->name('profile.user');
        Route::post('/profile/{id}/edit', [UserController::class, 'updateProfile'])->middleware('UserAccess:user')->name('profile.edit.user');

        Route::get('/item', [ItemController::class, 'showAll'])->middleware('UserAccess:user')->name('item.user');
        Route::post('/item/{id}/borrow', [BorrowController::class, 'borrow'])->middleware('UserAccess:user')->name('item.borrow');

        Route::get('/borrow', [BorrowController::class, 'showUser'])->middleware('UserAccess:user')->name('borrow.user');
        Route::get('/borrow/{id}/detail', [BorrowController::class, 'showUser'])->middleware('UserAccess:user')->name('borrow.detail');
        Route::post('/borrow/{id}/cancel', [BorrowController::class, 'canceled'])->middleware('UserAccess:user')->name('borrow.cancel');

        Route::get('/history', [BorrowController::class, 'historyUser'])->middleware('UserAccess:user')->name('history.user');
    });
});
