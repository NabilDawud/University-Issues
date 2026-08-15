<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeanshipController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
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
    Route::get('/get-roles-by-user-type/{userTypeId}', [UserController::class, 'getRolesByUserType'])->name('roles.by-user-type');
    Route::resource('admins', AdminController::class);
    Route::resource('students', StudentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('roles', RoleController::class)->except(['show', 'edit', 'update']);
    Route::get('roles/{role}/permissions', [RoleController::class, 'showPermissionsRole'])->name('roles.permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissionsRole'])->name('roles.permissions.update');
    Route::resource('permissions', PermissionController::class)->except(['show', 'edit', 'update']);
    Route::resource('categories', CategoryController::class);
    Route::resource('issues', IssueController::class);
    Route::get('issues/{issue}/reassign', [IssueController::class, 'showReassignForm'])->name('issues.reassign');
    Route::post('issues/{issue}/reassign', [IssueController::class, 'reassign']);
    Route::post('issues/{issue}/approve', [IssueController::class, 'approve'])->name('issues.approve');
    Route::post('issues/{issue}/reject', [IssueController::class, 'reject'])->name('issues.reject');
    Route::post('issues/{issue}/close', [IssueController::class, 'close'])->name('issues.close');
});