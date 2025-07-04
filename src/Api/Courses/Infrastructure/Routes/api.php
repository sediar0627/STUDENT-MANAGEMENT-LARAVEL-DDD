<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Courses\Infrastructure\Controllers\CreateCoursePOSTController;
use Src\Api\Courses\Infrastructure\Controllers\DeleteCourseByIdDELETEController;
use Src\Api\Courses\Infrastructure\Controllers\FindCourseByIdGETController;
use Src\Api\Courses\Infrastructure\Controllers\GetAllCoursesGETController;
use Src\Api\Courses\Infrastructure\Controllers\UpdateCoursePUTController;
use Src\Api\Courses\Infrastructure\Controllers\WithStudentsCountGETController;
use Src\Api\Courses\Infrastructure\Controllers\WithStudentsGETController;

Route::post('/', [CreateCoursePOSTController::class, 'handle']);
Route::get('/', [GetAllCoursesGETController::class, 'handle']);
Route::get('/with-students', [WithStudentsGETController::class, 'handle']);
Route::get('/enrollment-count', [WithStudentsCountGETController::class, 'handle']);
Route::get('/{id}', [FindCourseByIdGETController::class, 'handle']);
Route::put('/{id}', [UpdateCoursePUTController::class, 'handle']);
Route::delete('/{id}', [DeleteCourseByIdDELETEController::class, 'handle']);