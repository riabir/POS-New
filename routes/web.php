<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::resource('vendors', VendorController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('products', ProductController::class);
    Route::get('/getproducttypes', [ProductController::class, 'getProductTypes'])->name('get.product.types');
    Route::resource('purchases', PurchaseController::class);
    Route::get('/searchvendor', [PurchaseController::class, 'searchvendor'])->name('searchvendor');
    Route::get('/searchproduct', [PurchaseController::class, 'searchproduct'])->name('searchproduct');
    Route::resource('sales', SaleController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('product_types', ProductTypeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('brands', BrandController::class);

});
