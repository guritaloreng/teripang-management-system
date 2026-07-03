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
use App\Http\Controllers\ReceivingController;

Route::get('/', [HomeController::class, 'index']);

Route::resource('suppliers', SupplierController::class);

Route::resource('sea-cucumber-types', SeaCucumberTypeController::class);

Route::resource('investors', InvestorController::class);

Route::resource('investor-ledgers', InvestorLedgerController::class);

Route::resource('purchases', PurchaseController::class);

Route::get('/ocr', [OCRController::class, 'index'])->name('ocr.index');
Route::post('/ocr', [OCRController::class, 'upload'])->name('ocr.upload');

Route::resource('shipments', ShipmentController::class);

Route::resource('receivings', ReceivingController::class);