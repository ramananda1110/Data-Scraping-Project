<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\DataScrapingController;
use App\Http\Controllers\RequisitionController;




// Home route with name 'home'
Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/scrape-data', [DataScrapingController::class, 'data_scraping'])->name('scrape.data');
Route::get('/view-data', [DataScrapingController::class, 'showScrapedData'])->name('view.data');

Route::get('/fetch-requisitions', [RequisitionController::class, 'fetchAndStoreData']);
