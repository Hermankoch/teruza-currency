<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;


Route::get('/rates', [CurrencyController::class, 'getRates'])->name('getRates');



