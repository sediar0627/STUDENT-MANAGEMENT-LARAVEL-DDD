<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Courses\Infrastructure\Controllers\CreateCoursePOSTController;
use Src\Api\Courses\Infrastructure\Controllers\GetAllCoursesGETController;

Route::post('/', CreateCoursePOSTController::class);
Route::get('/', GetAllCoursesGETController::class);