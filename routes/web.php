<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('cms/admin/')->group(function () {
    Route::view('/parent', 'cms.parent');
    Route::view('/temp', 'cms.temp');
});