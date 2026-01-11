<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutePostController;

Route::post('/import-requisitions', [InstitutePostController::class, 'importBulk']);
