<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerAdjustmentController;
use App\Http\Controllers\CustomerAdvanceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLedgerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CustomerRefundController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ProfitReportController;
use App\Http\Controllers\SalaryStructureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleCommissionController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SerialSearchController;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\ShareholderTransactionController;
use App\Http\Controllers\VendorAdvanceController;
use App\Http\Controllers\VendorLedgerController;
use App\Http\Controllers\VendorRefundController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';

Route::get('/', fn() => redirect()->route('login'));

// --- ADMIN-ONLY ROUTES ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {});

// "DANGEROUS" PAYOUT ROUTES (ADMINS ONLY)
Route::middleware(['auth', 'role:admin'])->prefix('accounting')->name('accounting.')->group(function () {
  Route::resource('payouts', PayoutController::class)->only(['edit', 'update', 'destroy']);
});


Route::resource('users', UserController::class)->except(['show', 'destroy']);

Route::middleware('auth')->group(function () {
  // [--- Sale Controller----]
  Route::resource('sales', SaleController::class);
  //All Search -invoice 
  Route::get('/sales/{sale}/preview', [App\Http\Controllers\SaleController::class, 'showPreview'])->name('sales.preview');
  // Sales report - invoice 
  Route::get('/sales/preview/{sale}', [SaleController::class, 'showPreview'])->name('sales.showPreview');
  Route::get('/searchcustomer/{phone}', [SaleController::class, 'searchcustomer'])->name('searchcustomer');
  Route::resource('commissions', SaleCommissionController::class)->only(['index', 'show']);
  //-- Paid View
  Route::post('/sales/{sale}/mark-as-paid', [SaleController::class, 'markAsPaid'])->name('sales.markAsPaid');
  Route::get('/api/search-recipient/{phone}', [SaleController::class, 'searchRecipient']);
  Route::get('/search-recipient/{phone}', [SaleController::class, 'searchRecipient'])->name('search.recipient');

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  Route::resource('employees', EmployeeController::class);
  Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');

  Route::resource('users', UserController::class);
  Route::resource('vendors', VendorController::class);
  Route::resource('categories', CategorieController::class);
  Route::resource('product_types', ProductTypeController::class);
  Route::resource('customers', CustomerController::class);
  Route::resource('brands', BrandController::class);
  Route::resource('salary_structures', SalaryStructureController::class);
    Route::resource('shareholders', ShareholderController::class);

  Route::resource('payouts', PayoutController::class);
  Route::resource('sale_commissions', SaleCommissionController::class);
  Route::resource('expense_types', ExpenseTypeController::class);
  Route::get('/get-brand-product-types', [BrandController::class, 'getProductTypes'])->name('brands.getProductTypes');
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Product Controller
  Route::resource('products', ProductController::class);
  Route::get('/getproducttypes', [ProductController::class, 'getProductTypes'])->name('get.product.types');
  Route::get('/search-product', [ProductController::class, 'search'])->name('search.product');

  // --- PURCHASE FEATURE ROUTES ---
  Route::resource('purchases', PurchaseController::class);

  //all search/invoice
  Route::get('/purchases/{purchase}/preview', [PurchaseController::class, 'showPreview'])->name('purchases.preview');
  // sales report/ invoice
  Route::get('/purchases/preview/{purchase}', [PurchaseController::class, 'showPreview'])->name('purchases.showPreview');

  Route::get('/searchvendor/{phone}', [PurchaseController::class, 'searchvendor'])->name('searchvendor');
  // AJAX route to search for products by term
  Route::get('/search-products', [PurchaseController::class, 'searchProducts'])->name('products.search');

  // [-- Stock Controller----]
  Route::get('/get-lsp/{productId}', [StockController::class, 'getLSP']);
  Route::resource('stocks', App\Http\Controllers\StockController::class)->only(['index']);



  // Vendor Account Controller
  Route::post('/vendor-accounts/{vendor_account}/process-payment', [VendorAccountController::class, 'processPayment'])->name('vendor_accounts.processPayment');
  Route::get('/vendor-accounts', [VendorAccountController::class, 'index'])->name('vendor_accounts.index');


  // Vendor Advance Payments
  Route::resource('vendor_advances', VendorAdvanceController::class);
  Route::get('/vendor-advances/create', [VendorAdvanceController::class, 'create'])->name('vendor_advances.create');
  Route::post('/vendor-advances', [VendorAdvanceController::class, 'store'])->name('vendor_advances.store');
  // Vendor Ledgers
  Route::resource('vendor_ledgers', VendorLedgerController::class);
  Route::get('/vendor-ledgers', [VendorLedgerController::class, 'index'])->name('vendor.ledgers.index');
  Route::get('/vendor-ledgers/{vendor}', [VendorLedgerController::class, 'show'])->name('vendor.ledgers.show');


  // Customer Advance Routes
  Route::get('/customer-advances/create', [CustomerAdvanceController::class, 'create'])->name('customer_advances.create');
  Route::post('/customer-advances', [CustomerAdvanceController::class, 'store'])->name('customer_advances.store');


  // Customer Ledger Routes
  Route::get('/customer-ledgers', [CustomerLedgerController::class, 'index'])->name('customer_ledgers.index');
  Route::get('/customer-ledgers/{customer}', [CustomerLedgerController::class, 'show'])->name('customer_ledgers.show');


  // Customer Adjustment
  Route::get('/customer-adjustments/{customer}/create', [CustomerAdjustmentController::class, 'create'])->name('customer_adjustments.create');
  Route::post('/customer-adjustments/{customer}', [CustomerAdjustmentController::class, 'store'])->name('customer_adjustments.store');

  // Customer Account Routes
  Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer_accounts.index');
  Route::post('/customer-accounts/process-payment/{customer_account}', [CustomerAccountController::class, 'processPayment'])->name('customer_accounts.processPayment');
  Route::get('/customer-accounts/{customer_account}/ledger', [CustomerAccountController::class, 'showLedger'])->name('customer_accounts.show_ledger');

  Route::get('payouts/bulk/create', [PayoutController::class, 'createBulk'])->name('payouts.createBulk');
  Route::post('payouts/bulk', [PayoutController::class, 'storeBulk'])->name('payouts.storeBulk');


  // Routes for adding new transactions to a specific shareholder's ledger
  Route::get('shareholders/{shareholder}/transactions/create', [ShareholderTransactionController::class, 'create'])->name('shareholder_transactions.create');
  Route::post('shareholders/{shareholder}/transactions', [ShareholderTransactionController::class, 'store'])->name('shareholder_transactions.store');


  // Route for the serial number tracker
  Route::get('/serial-tracker', [SerialSearchController::class, 'index'])->name('serial.search');

  // --- PROFIT REPORT ROUTES ---
  Route::get('/profit-report', [ProfitReportController::class, 'index'])->name('profit.index');
  Route::get('/profit-report/{sale}', [ProfitReportController::class, 'show'])->name('profit.show');

  Route::get('/summary-report', [ReportController::class, 'summaryReport'])->name('reports.summary');

  // --- EXPENSE MANAGEMENT ROUTES ---
  Route::get('/my-expenses', [ExpenseController::class, 'userIndex'])->name('expenses.user.index'); // <-- User's view
  Route::get('/expenses/paid/download', [ExpenseController::class, 'downloadPaidReport'])->name('expenses.paid.download'); // <-- PDF Download
  Route::get('/expenses/paid', [ExpenseController::class, 'listPaid'])->name('expenses.paid');
  Route::get('/expenses/{expense}/approve', [ExpenseController::class, 'showApproveForm'])->name('expenses.approve.form');
  Route::post('/expenses/{expense}/approve', [ExpenseController::class, 'processApproval'])->name('expenses.approve.process');
  Route::get('/expenses/{expense}/pay', [ExpenseController::class, 'showPayForm'])->name('expenses.pay.form');
  Route::post('/expenses/{expense}/pay', [ExpenseController::class, 'processPayment'])->name('expenses.pay.process');
  Route::resource('expenses', ExpenseController::class)->except(['show', 'edit', 'update', 'destroy']);

  // Customer Refund Routes
  Route::get('/customer-refunds', [CustomerRefundController::class, 'index'])->name('customer_refunds.index');
  Route::get('/customer-refunds/create', [CustomerRefundController::class, 'create'])->name('customer_refunds.create');
  Route::get('/customer-refunds/{customer}/create', [CustomerRefundController::class, 'createForCustomer'])->name('customer_refunds.create_for_customer');
  Route::post('/customer-refunds/{customer}', [CustomerRefundController::class, 'store'])->name('customer_refunds.store');
// Vendor Refund Routes
  Route::get('vendor-refunds', [VendorRefundController::class, 'index'])->name('vendor_refunds.index');
  Route::get('vendor-refunds/{vendor}/create', [VendorRefundController::class, 'create'])->name('vendor_refunds.create');
  Route::post('vendor-refunds/{vendor}', [VendorRefundController::class, 'store'])->name('vendor_refunds.store');
// Shareholder Refund Routes

});
require __DIR__ . '/auth.php';
