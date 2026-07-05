<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SeaCucumberTypeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestorLedgerController;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CashTransactionController;

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
| Shipment
|--------------------------------------------------------------------------
*/

Route::resource('shipments', ShipmentController::class);

/*
|--------------------------------------------------------------------------
| Sale (Create From Shipment)
|--------------------------------------------------------------------------
*/

Route::get(
    '/shipments/{shipment}/sale',
    [SaleController::class, 'create']
)->name('sales.create');

Route::post(
    '/shipments/{shipment}/sale',
    [SaleController::class, 'store']
)->name('sales.store');

/*
|--------------------------------------------------------------------------
| Sale Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/sales',
    [SaleController::class, 'index']
)->name('sales.index');

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
| Shipment Workflow
|--------------------------------------------------------------------------
*/

Route::post(
    '/shipments/{shipment}/arrived',
    [ShipmentController::class, 'arrived']
)->name('shipments.arrived');

Route::post(
    '/shipments/{shipment}/complete',
    [ShipmentController::class, 'complete']
)->name('shipments.complete');
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