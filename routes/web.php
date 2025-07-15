<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerAdjustmentController;
use App\Http\Controllers\CustomerAdvanceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLedgerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\VendorAdvanceController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\VendorLedgerController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::get('/', function () {
  return view('welcome'); });
require __DIR__ . '/auth.php';

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth', 'verified'])->group(function () {

  // --- ROUTES FOR ALL LOGGED-IN USERS ---
  Route::get('/dashboard', function () {
    return view('dashboard'); })->name('dashboard');
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::resource('sales', SaleController::class);
  Route::get('/sales/preview/{sale}', [SaleController::class, 'showPreview'])->name('sales.preview');
  Route::post('/sales/{sale}/mark-as-paid', [SaleController::class, 'markAsPaid'])->name('sales.markAsPaid');
  Route::get('/searchcustomer/{phone}', [SaleController::class, 'searchcustomer'])->name('searchcustomer');
  Route::resource('customers', CustomerController::class);


  // --- ROUTES FOR ACCOUNTS & ADMINS ---
  Route::middleware('role:admin,accounts')->group(function () {
    Route::resource('vendors', VendorController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases/preview/{purchase}', [PurchaseController::class, 'showPreview'])->name('purchases.preview');
    Route::get('/searchvendor/{phone}', [PurchaseController::class, 'searchvendor'])->name('searchvendor');
    Route::resource('expenses', ExpenseController::class);
    Route::resource('vendor_ledgers', VendorLedgerController::class);
    Route::resource('vendor_advances', VendorAdvanceController::class);
    Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer_accounts.index');
    Route::post('/customer-accounts/process-payment/{customer_account}', [CustomerAccountController::class, 'processPayment'])->name('customer_accounts.processPayment');
    Route::get('/customer_ledgers', [CustomerLedgerController::class, 'index'])->name('customer_ledgers.index');
    Route::get('/customer-ledgers/{customer}', [CustomerLedgerController::class, 'show'])->name('customer_ledgers.show');
    Route::get('/customer_advances/create', [CustomerAdvanceController::class, 'create'])->name('customer_advances.create');
    Route::post('/customer_advances', [CustomerAdvanceController::class, 'store'])->name('customer_advances.store');
    Route::get('/vendor-accounts', [VendorAccountController::class, 'index'])->name('vendor_accounts.index');
    Route::post('/vendor-accounts/{vendor_account}/process-payment', [VendorAccountController::class, 'processPayment'])->name('vendor_accounts.processPayment');
  });

  // --- ROUTES FOR ADMINS ONLY ---
  Route::middleware('role:admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::resource('employees', EmployeeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategorieController::class);
    Route::resource('product_types', ProductTypeController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('stocks', StockController::class)->only(['index']);
    Route::get('/getproducttypes', [ProductController::class, 'getProductTypes'])->name('get.product.types');
  });
});