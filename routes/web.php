<?php

use App\Http\Controllers\DeanshipController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('cms/admin/')->name('admin.')->group(function () {
    Route::view('/parent', 'cms.parent');
    Route::view('/temp', 'cms.temp');
    Route::resource('deanships', DeanshipController::class);
});