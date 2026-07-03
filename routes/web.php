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

Route::get('/', [HomeController::class, 'index']);

Route::resource('suppliers', SupplierController::class);

Route::resource('sea-cucumber-types', SeaCucumberTypeController::class);

Route::resource('investors', InvestorController::class);

Route::resource('investor-ledgers', InvestorLedgerController::class);

Route::resource('purchases', PurchaseController::class);

Route::get('/ocr', [OCRController::class, 'index'])->name('ocr.index');
Route::post('/ocr', [OCRController::class, 'upload'])->name('ocr.upload');

Route::resource('shipments', ShipmentController::class);

/*
|--------------------------------------------------------------------------
| Shipment Workflow
|--------------------------------------------------------------------------
*/

Route::get(
    '/shipments/{shipment}/sale',
    [ShipmentController::class, 'createSale']
)->name('shipments.sale.create');

Route::post(
    '/shipments/{shipment}/sale',
    [ShipmentController::class, 'storeSale']
)->name('shipments.sale.store');

Route::post(
    '/shipments/{shipment}/complete',
    [ShipmentController::class, 'complete']
)->name('shipments.complete');

Route::post(
    '/shipments/{shipment}/arrived',
    [ShipmentController::class, 'arrived']
)->name('shipments.arrived');