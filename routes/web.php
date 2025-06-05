<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\DataScrapingController;
use App\Http\Controllers\RequisitionController;

use App\Http\Controllers\AllRequisitionController;
use App\Http\Controllers\SubjectController;



// Home route with name 'home'
Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/scrape-data', [DataScrapingController::class, 'data_scraping'])->name('scrape.data');
Route::get('/view-data', [DataScrapingController::class, 'showScrapedData'])->name('view.data');

Route::get('/fetch-requisitions', [RequisitionController::class, 'fetchAndStoreData']);

Route::get('/all-info', [RequisitionController::class, 'getAllInfo']);


Route::get('/fetch-all-requisitions', [AllRequisitionController::class, 'fetchAndStoreData']);
Route::get('/all-requisitions', [AllRequisitionController::class, 'getAllRecords']);

Route::get('/all-requisitions-info', [AllRequisitionController::class, 'getAllInfo'])->name('requisitions.district');

Route::get('/requisitions', [AllRequisitionController::class, 'index'])->name('requisitions.index');

Route::get('/bangla-info', [SubjectController::class, 'getBanglaInfo'])->name('subjects.bangla');


Route::get('/scrape-final-result', [DataScrapingController::class, 'finalDataScraping']);
