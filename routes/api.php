<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('courses')->group(base_path('src/Api/Courses/Infrastructure/Routes/api.php'));
});