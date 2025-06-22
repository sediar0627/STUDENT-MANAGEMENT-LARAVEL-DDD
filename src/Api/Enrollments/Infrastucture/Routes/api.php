<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Enrollments\Infrastucture\Controllers\CreateEnrollmentPOSTController;

Route::post('/', [CreateEnrollmentPOSTController::class, 'handle']);