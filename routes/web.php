<?php

use App\Http\Controllers\Business\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
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

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});

Auth::routes();
Route::middleware(['auth', 'UserExist'])->group(function () {

    Route::middleware(['super_admin'])->group(function () {

        Route::get('/users', [UserManagementController::class, 'index'])->name('business.users');
        Route::get('/users/create', [UserManagementController::class, 'create_form'])->name('business.users.create.form');
        Route::post('/users/create', [UserManagementController::class, 'create'])->name('business.users.create');



    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


