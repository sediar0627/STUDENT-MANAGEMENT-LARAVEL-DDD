<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Courses\Infrastructure\Controllers\CreateCoursePOSTController;

Route::post('/', CreateCoursePOSTController::class);