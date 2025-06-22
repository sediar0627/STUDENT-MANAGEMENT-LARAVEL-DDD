<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Students\Infrastructure\Controllers\CreateStudentPOSTController;
use Src\Api\Students\Infrastructure\Controllers\DeleteStudentByIdDELETEController;
use Src\Api\Students\Infrastructure\Controllers\FindStudentByIdGETController;
use Src\Api\Students\Infrastructure\Controllers\GetAllStudentsGETController;
use Src\Api\Students\Infrastructure\Controllers\UpdateStudentPUTController;

Route::post('/', CreateStudentPOSTController::class);
Route::get('/', GetAllStudentsGETController::class);
Route::get('/{id}', FindStudentByIdGETController::class);
Route::put('/{id}', UpdateStudentPUTController::class);
Route::delete('/{id}', DeleteStudentByIdDELETEController::class);
