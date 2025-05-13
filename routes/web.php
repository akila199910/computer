<?php

use App\Http\Controllers\Business\BrandController;
use App\Http\Controllers\Business\CategoryController;
use App\Http\Controllers\Business\OrderController;
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
        Route::get('/brands/update/{id}', [BrandController::class, 'update_form'])->name('business.brands.update.form');
        Route::post('/brands/update', [BrandController::class, 'update'])->name('business.brands.update');
        Route::post('/brands/delete', [BrandController::class, 'delete'])->name('business.brands.delete');
        Route::get('/brands/view/{ref_no}', [BrandController::class, 'view_details'])->name('business.brands.view_details');
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
    Route::get('/categories/update/{id}', [CategoryController::class, 'update_form'])->name('business.category.update.form');
    Route::post('/categories/update', [CategoryController::class, 'update'])->name('business.category.update');
    Route::post('/categories/delete', [CategoryController::class, 'delete'])->name('business.category.delete');
    Route::get('/categories/view/{ref_no}', [CategoryController::class, 'view_details'])->name('business.category.view_details');


    Route::get('/products', [ProductController::class, 'index'])->name('business.product');
    Route::get('/products/create', [ProductController::class, 'create_form'])->name('business.product.create.form');
    Route::post('/products/create', [ProductController::class, 'create'])->name('business.product.create');
    Route::get('/products/update/{id}', [ProductController::class, 'update_form'])->name('business.product.update.form');
    Route::post('/products/update', [ProductController::class, 'update'])->name('business.product.update');
    Route::post('/products/delete', [ProductController::class, 'delete'])->name('business.product.delete');
    Route::get('/products/view/{ref_no}', [ProductController::class, 'view_details'])->name('business.product.view_details');

    Route::get('/orders', [OrderController::class, 'index'])->name('business.order');
    Route::get('/orders/create', [OrderController::class, 'create_form'])->name('business.order.create.form');
    Route::post('/orders/create', [OrderController::class, 'create'])->name('business.order.create');
    Route::get('/orders/update/{id}', [OrderController::class, 'update_form'])->name('business.order.update.form');
    Route::post('/orders/update', [OrderController::class, 'update'])->name('business.order.update');
    Route::post('/orders/delete', [OrderController::class, 'delete'])->name('business.order.delete');
    Route::get('/orders/view/{ref_no}', [OrderController::class, 'view_details'])->name('business.order.view_details');
    Route::post('/orders/change', [OrderController::class, 'change'])->name('business.order.change');



    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


