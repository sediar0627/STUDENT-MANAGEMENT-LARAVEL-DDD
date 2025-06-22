<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Students\Infrastructure\Controllers\CreateStudentPOSTController;
use Src\Api\Students\Infrastructure\Controllers\UpdateStudentPUTController;

Route::post('/', CreateStudentPOSTController::class);
// Route::get('/', GetAllCoursesGETController::class);
// Route::get('/{id}', FindCourseByIdGETController::class);
Route::put('/{id}', UpdateStudentPUTController::class);
// Route::delete('/{id}', DeleteCourseByIdDELETEController::class);
