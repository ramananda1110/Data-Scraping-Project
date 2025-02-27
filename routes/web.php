<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\DataScrapingController;




Route::get('/', [HomeController::class, 'index']);

Route::get('/scrape-data', [DataScrapingController::class, 'data_scraping']);
