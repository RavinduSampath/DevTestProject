<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\MeterReadingsController;

Route::get('/', [CustomersController::class, 'index'])
    ->name('customers.index');
// Route::get('/get-customer-bill', [CustomersController::class, 'show'])
//     ->name('customers.show');
Route::get('/customers/search', [CustomersController::class, 'search'])->name('pages.customers.search');
Route::get('/customers/{account_number}', [CustomersController::class, 'details'])->name('pages.customers.details');

Route::get('/meter-reader', [MeterReadingsController::class, 'index'])->name('meter-reader.index');

Route::post('/meter-reader', [MeterReadingsController::class, 'store'])->name('meter-reader.store');
