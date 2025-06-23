<?php

use App\Http\Controllers\ApiLoginController;
use App\Http\Middleware\LogEndpointHit;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware([LogEndpointHit::class])
    ->group(function () {

        Route::post('login', [ApiLoginController::class, 'handle']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::prefix('courses')->group(base_path('src/Api/Courses/Infrastructure/Routes/api.php'));
            Route::prefix('students')->group(base_path('src/Api/Students/Infrastructure/Routes/api.php'));
            Route::prefix('enrollments')->group(base_path('src/Api/Enrollments/Infrastucture/Routes/api.php'));
        });

    });
