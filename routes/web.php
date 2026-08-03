<?php

use App\Http\Controllers\DeanshipController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MajorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('cms/admin/')->name('admin.')->group(function () {
    Route::view('/parent', 'cms.parent');
    Route::view('/temp', 'cms.temp');
    Route::resource('deanships', DeanshipController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('majors', MajorController::class);
});