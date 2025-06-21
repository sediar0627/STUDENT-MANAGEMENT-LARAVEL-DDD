<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Students\Infrastructure\Controllers\CreateStudentPOSTController;

Route::post('/', CreateStudentPOSTController::class);
