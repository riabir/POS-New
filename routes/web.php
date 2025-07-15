<?php

use App\Http\Controllers\BrandController;
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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\VendorAdvanceController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\VendorLedgerController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Management
    // The link for "User Registration" points to users.create
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');


Route::middleware('auth')->group(function () {
  Route::resource('employees', EmployeeController::class);
  Route::resource('vendors', VendorController::class);
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  Route::resource('products', ProductController::class);
  Route::get('/getproducttypes', [ProductController::class, 'getProductTypes'])->name('get.product.types');
  Route::resource('sales', SaleController::class);
  Route::resource('expenses', ExpenseController::class);
  Route::resource('categories', CategorieController::class);
  Route::resource('product_types', ProductTypeController::class);
  Route::resource('customers', CustomerController::class);
  Route::resource('brands', BrandController::class);
  Route::get('/search-product', [ProductController::class, 'search'])->name('search.product');
  Route::resource('vendor_ledgers', VendorLedgerController::class);
  Route::resource('vendor_advances', VendorAdvanceController::class);

  // --- PURCHASE FEATURE ROUTES ---
  Route::resource('purchases', PurchaseController::class);

  // --- AJAX SEARCH ROUTES for the purchase form ---
  Route::get('/searchvendor/{phone}', [PurchaseController::class, 'searchvendor'])->name('searchvendor');
  Route::get('/searchcustomer/{phone}', [SaleController::class, 'searchcustomer'])->name('searchcustomer');

  // AJAX route to search for products by term
  Route::get('/search-products', [PurchaseController::class, 'searchProducts'])->name('products.search');
  Route::get('/searchproduct', [PurchaseController::class, 'searchproduct'])->name('searchproduct');


  Route::resource('stocks', App\Http\Controllers\StockController::class)->only(['index']);

  // {---Vendor Account Controller Start--}
  // This is the main page for viewing outstanding bills.
    Route::get('/vendor-accounts', [VendorAccountController::class, 'index'])->name('vendor_accounts.index');

  Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer_accounts.index');


  // MODIFICATION: New single route for all payment processing actions.
  Route::post('/vendor-accounts/{vendor_account}/process-payment', [VendorAccountController::class, 'processPayment'])->name('vendor_accounts.processPayment');


  Route::get('/customer-ledgers', [CustomerLedgerController::class, 'index'])->name('customer.ledgers.index');



    // Vendor Advance Payments
    Route::get('/vendor-advances/create', [VendorAdvanceController::class, 'create'])->name('vendor_advances.create');
    Route::post('/vendor-advances', [VendorAdvanceController::class, 'store'])->name('vendor_advances.store');


 
  Route::get('/customer_advances/create', [CustomerAdvanceController::class, 'create'])->name('customer_advances.create');

  // The action to store the new advance payment.
  
  Route::post('/customer_advances', [CustomerAdvanceController::class, 'store'])->name('customer_advances.store');

 // Vendor Ledgers
    Route::get('/vendor-ledgers', [VendorLedgerController::class, 'index'])->name('vendor.ledgers.index');
    Route::get('/vendor-ledgers/{vendor}', [VendorLedgerController::class, 'show'])->name('vendor.ledgers.show');


  // The list of all vendors to choose from.
 
  Route::get('/customer_ledgers', [CustomerLedgerController::class, 'index'])->name('customer.ledgers.index');
  Route::get('/vendor-ledgers/{vendor}', [VendorLedgerController::class, 'show'])->name('vendor.ledgers.show');
  Route::get('/customer-ledgers/{customer}', [CustomerLedgerController::class, 'show'])->name('customer.ledgers.show');

  Route::get('/get-lsp/{productId}', [StockController::class, 'getLSP']);

  Route::get('/customer-ledgers', [CustomerLedgerController::class, 'index'])->name('customer_ledgers.index');
  Route::get('/customer-ledgers/{customer}', [CustomerLedgerController::class, 'show'])->name('customer_ledgers.show');

  Route::post('/sales/{sale}/mark-as-paid', [SaleController::class, 'markAsPaid'])->name('sales.markAsPaid');
  Route::resource('sales', SaleController::class); // Keep this one

  // New Dedicated Payment Flow

  // New Adjustment Flow
  Route::get('/customer-adjustments/{customer}/create', [CustomerAdjustmentController::class, 'create'])->name('customer_adjustments.create');
  Route::post('/customer-adjustments/{customer}', [CustomerAdjustmentController::class, 'store'])->name('customer_adjustments.store');

  // This is the list of due bills
  Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer_accounts.index');
  // THIS IS THE MISSING ROUTE TO ADD
  Route::post('/customer-accounts/process-payment/{customer_account}', [CustomerAccountController::class, 'processPayment'])->name('customer_accounts.processPayment');

  Route::get('/customer-accounts/{customer_account}/ledger', [CustomerAccountController::class, 'showLedger'])->name('customer_accounts.show_ledger');

});
require __DIR__ . '/auth.php';
