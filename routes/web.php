<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Dashboard
    Route::get('dashboard', ['App\Http\Controllers\Admin\DashboardController', 'index'])->name('dashboard');

    Route::resource('student', App\Http\Controllers\Admin\StudentController::class);
    Route::get('/summary-point', [App\Http\Controllers\Admin\StudentController::class, 'summaryPoint'])->name('summary.point');
    Route::get('/generate-certificate/{studentId}', [App\Http\Controllers\Admin\StudentController::class, 'generateCertificate'])->name('generate.certificate');


    Route::resource('activity-category', App\Http\Controllers\Admin\ActivityCategoryController::class);
    Route::resource('activity-type', App\Http\Controllers\Admin\ActivityTypeController::class);
    Route::resource('level', App\Http\Controllers\Admin\LevelController::class);
    Route::resource('award', App\Http\Controllers\Admin\AwardController::class);

    Route::resource('activity', App\Http\Controllers\Admin\ActivityController::class);
    Route::get('/get-activity-details', [App\Http\Controllers\Admin\ActivityController::class, 'getActivityDetails']);
    Route::put('/activity/{activity}/status/{status}', [App\Http\Controllers\Admin\ActivityController::class, 'updateStatus'])->name('activity.updateStatus');
    Route::get('/activities/export', [App\Http\Controllers\Admin\ActivityController::class, 'export'])->name('activity.export');

    // User
    Route::resource('user', App\Http\Controllers\Admin\UserController::class);

    // Role
    Route::resource('role', App\Http\Controllers\Admin\RoleController::class);
});
