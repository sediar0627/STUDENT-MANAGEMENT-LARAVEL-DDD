<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Courses\Infrastructure\Controllers\CreateCoursePOSTController;
use Src\Api\Courses\Infrastructure\Controllers\DeleteCourseByIdDELETEController;
use Src\Api\Courses\Infrastructure\Controllers\FindCourseByIdGETController;
use Src\Api\Courses\Infrastructure\Controllers\GetAllCoursesGETController;
use Src\Api\Courses\Infrastructure\Controllers\UpdateCoursePUTController;

Route::post('/', CreateCoursePOSTController::class);
Route::get('/', GetAllCoursesGETController::class);
Route::get('/{id}', FindCourseByIdGETController::class);
Route::put('/{id}', UpdateCoursePUTController::class);
Route::delete('/{id}', DeleteCourseByIdDELETEController::class);