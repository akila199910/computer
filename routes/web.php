<?php

use App\Http\Controllers\Business\BrandController;
use App\Http\Controllers\Business\CategoryController;
use App\Http\Controllers\Business\ProductController;
use App\Http\Controllers\Business\UserManagementController;
use App\Http\Controllers\DashboardController;
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

    Route::get('/brands', [BrandController::class, 'index'])->name('business.brands');
    Route::get('/brands/create', [BrandController::class, 'create_form'])->name('business.brands.create.form');
    Route::post('/brands/create', [BrandController::class, 'create'])->name('business.brands.create');
    Route::get('/brands/update/{id}', [BrandController::class, 'update_form'])->name('business.brands.update.form');
    Route::post('/brands/update', [BrandController::class, 'update'])->name('business.brands.update');
    Route::post('/brands/delete', [BrandController::class, 'delete'])->name('business.brands.delete');
    Route::get('/brands/view/{ref_no}', [BrandController::class, 'view_details'])->name('business.brands.view_details');


    Route::get('/categories', [CategoryController::class, 'index'])->name('business.category');
    Route::get('/categories/create', [CategoryController::class, 'create_form'])->name('business.category.create.form');
    Route::post('/categories/create', [CategoryController::class, 'create'])->name('business.category.create');


    Route::get('/products', [ProductController::class, 'index'])->name('business.product');
    Route::get('/products/create', [ProductController::class, 'create_form'])->name('business.product.create.form');
    Route::post('/products/create', [ProductController::class, 'create'])->name('business.product.create');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


