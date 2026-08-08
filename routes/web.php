<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeanshipController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('cms/admin/')->middleware(['auth'])->name('admin.')->group(function () {
    Route::view('/', 'cms.parent')->name('dashboard');
    Route::view('/temp', 'cms.temp');
    Route::resource('deanships', DeanshipController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('users', UserController::class);
});