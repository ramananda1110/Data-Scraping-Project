<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\DataScrapingController;
use App\Http\Controllers\RequisitionController;

use App\Http\Controllers\AllRequisitionController;
use App\Http\Controllers\RequisitionNewController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\InstitutePostController;

use App\Http\Controllers\MeritListController;


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

Route::get('/all-requisitions-7th', [RequisitionNewController::class, 'getAllRecords7th']);

Route::get('/fetch-all-requisitions-7th', [RequisitionNewController::class, 'fetchAndStoreData7th']);


Route::get('/all-requisitions-info', [RequisitionNewController::class, 'getAllInfoBangla'])->name('requisitions.district');

Route::get('/requisitions', [AllRequisitionController::class, 'index'])->name('requisitions.index');
Route::get('/requisitions-7th', [RequisitionNewController::class, 'index'])->name('requisitions.index_7th');


Route::get('/bangla-info', [SubjectController::class, 'getBanglaInfo'])->name('subjects.bangla');


Route::get('/scrape-final-result', [DataScrapingController::class, 'finalDataScraping']);

Route::get('/scrape-final-result-physics', [DataScrapingController::class, 'finalDataScrapingPhysics']);

Route::get('/scrape-final-result-political-science', [DataScrapingController::class, 'finalResultPoliticalScience']);


Route::get('/scrape-data-17', [DataScrapingController::class, 'data_scraping17']);

Route::get('all-vacancy/download-pdf', [AllRequisitionController::class, 'exportingPdfVecancy'])->name('vacancy.exportPdf');

Route::get('all-vacancy/download-csv', [RequisitionNewController::class, 'exportingCsvVacancy'])->name('vacancy.exportCsv');
Route::get('all-vacancy-7th/download-pdf', [RequisitionNewController::class, 'exportingPdfVecancy'])->name('vacancy.exportPdf_7th');

Route::get('all-vacancy-7th/download-pdf', [RequisitionNewController::class, 'exportingPdfVecancy'])->name('vacancy.exportPdf7th');

Route::get('/demonstrator-checking-data', [DataScrapingController::class, 'data_scrapingBotany']);




// collected data for recommend meril list

Route::get('/merit-lists', [MeritListController::class, 'index'])->name('recommended.index');
Route::get('/fetch-merit-lists', [MeritListController::class, 'fetchAndStore']);
Route::get('/update-recommended-institutes', [MeritListController::class, 'updateRecommendedInstitutes']);
Route::get('/all-merit/merit-position-pdf', [MeritListController::class, 'exportingPdfSelectCandidate'])->name('merit.exportPdf');

Route::get('/district-wise-marks', [MeritListController::class, 'recommendedDistrictLecturer'])->name('district-wise.marks');
Route::get('/district-raw-data-list-marks', [MeritListController::class, 'recommendedRawDistrictLecturer'])->name('district-wise.raw');

Route::get('/not-recommended-count', [MeritListController::class, 'naRecommendedMarksCountPivot'])->name('na-recommendation.raw');

Route::get('/remining-merit-marks', [MeritListController::class, 'getRemainingMeritByDistrict'])->name('remaining-merit.raw');

Route::get('/requisitions-bangla-7th', [InstitutePostController::class, 'index'])->name('requisitions.bangla');


Route::get('/vacancies-by-district', [InstitutePostController::class, 'vacanciesByDistrict']);


Route::get('/recommended-by-district', [MeritListController::class, 'recommendedByDistrict']);
