<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SeaCucumberTypeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestorLedgerController;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\StockOverviewController;

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Master Data
|--------------------------------------------------------------------------
*/

Route::resource('suppliers', SupplierController::class);

Route::resource('sea-cucumber-types', SeaCucumberTypeController::class);

Route::resource('investors', InvestorController::class);

Route::resource('investor-ledgers', InvestorLedgerController::class);

/*
|--------------------------------------------------------------------------
| Purchase
|--------------------------------------------------------------------------
*/

Route::resource('purchases', PurchaseController::class);

/*
|--------------------------------------------------------------------------
| OCR Purchase
|--------------------------------------------------------------------------
*/

Route::get('/ocr', [OCRController::class, 'index'])
    ->name('ocr.index');

Route::post('/ocr', [OCRController::class, 'upload'])
    ->name('ocr.upload');

/*
|--------------------------------------------------------------------------
| Sale Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/sales/create',
    [SaleController::class, 'create']
)->name('sales.create');

Route::post(
    '/sales',
    [SaleController::class, 'store']
)->name('sales.store');

Route::post(
    '/sales/scan-note',
    [OCRController::class, 'scanSaleNote']
)->name('sales.scan-note');

Route::get(
    '/sales/scan-note',
    [SaleController::class, 'create']
)->name('sales.scan-note.form');

Route::get(
    '/sales',
    [SaleController::class, 'index']
)->name('sales.index');

Route::get(
    '/sales/{sale}/edit',
    [SaleController::class, 'edit']
)->name('sales.edit');

Route::match(
    ['put', 'patch'],
    '/sales/{sale}',
    [SaleController::class, 'update']
)->name('sales.update');

Route::get(
    '/sales/{sale}',
    [SaleController::class, 'show']
)->name('sales.show');

Route::delete(
    '/sales/{sale}',
    [SaleController::class, 'destroy']
)->name('sales.destroy');

/*
|--------------------------------------------------------------------------
| Expense
|--------------------------------------------------------------------------
*/

Route::resource(
    'expenses',
    ExpenseController::class
    
);
/*
|--------------------------------------------------------------------------
| Cash Book
|--------------------------------------------------------------------------
*/

Route::get(
    '/cash-book',
    [CashTransactionController::class, 'index']
)->name('cash-book.index');

/*
|--------------------------------------------------------------------------
| Stock Overview
|--------------------------------------------------------------------------
*/

Route::get(
    '/stock-overview',
    [StockOverviewController::class, 'index']
)->name('stock-overview.index');

Route::patch(
    '/stock-overview/{seaCucumberType}',
    [StockOverviewController::class, 'updateStatus']
)->name('stock-overview.update-status');

/*
|--------------------------------------------------------------------------
| Backup
|--------------------------------------------------------------------------
*/

Route::get(
    '/backups',
    [BackupController::class, 'index']
)->name('backups.index');

Route::post(
    '/backups',
    [BackupController::class, 'store']
)->name('backups.store');
