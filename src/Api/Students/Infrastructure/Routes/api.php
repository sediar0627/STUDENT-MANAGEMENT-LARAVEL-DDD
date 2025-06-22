<?php

use Illuminate\Support\Facades\Route;
use Src\Api\Students\Infrastructure\Controllers\CreateStudentPOSTController;
use Src\Api\Students\Infrastructure\Controllers\DeleteStudentByIdDELETEController;
use Src\Api\Students\Infrastructure\Controllers\FindStudentByIdGETController;
use Src\Api\Students\Infrastructure\Controllers\GetAllStudentsGETController;
use Src\Api\Students\Infrastructure\Controllers\UpdateStudentPUTController;

Route::post('/', [CreateStudentPOSTController::class, 'handle']);
Route::get('/', [GetAllStudentsGETController::class, 'handle']);
Route::get('/{id}', [FindStudentByIdGETController::class, 'handle']);
Route::put('/{id}', [UpdateStudentPUTController::class, 'handle']);
Route::delete('/{id}', [DeleteStudentByIdDELETEController::class, 'handle']);
