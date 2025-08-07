<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;

Route::get('/', [CustomersController::class, 'index'])
    ->name('customers.index');
// Route::get('/get-customer-bill', [CustomersController::class, 'show'])
//     ->name('customers.show');
Route::get('/customers/search', [CustomersController::class, 'search'])->name('pages.customers.search');
Route::get('/customers/{account_number}', [CustomersController::class, 'details'])->name('pages.customers.details');
