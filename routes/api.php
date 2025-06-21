<?php

use App\Http\Middleware\LogEndpointHit;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware([LogEndpointHit::class])
    ->group(function () {
        Route::prefix('courses')->group(base_path('src/Api/Courses/Infrastructure/Routes/api.php'));
        Route::prefix('students')->group(base_path('src/Api/Students/Infrastructure/Routes/api.php'));
    });