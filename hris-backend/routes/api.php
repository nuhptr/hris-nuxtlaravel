<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\ResponsibilityController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;

$sanctum = 'auth:sanctum';

Route::middleware($sanctum)->get('/user', function (Request $request) {
    return $request->user();
});

// Auth API
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});

// Company API
Route::prefix('company')->middleware($sanctum)->name('company.')->group(function () {
    Route::get('', [CompanyController::class, 'fetch'])->name('fetch');
    Route::post('', [CompanyController::class, 'create'])->name('create');
    Route::post('update/{id}', [CompanyController::class, 'update'])->name('update');
});

// Team API
Route::prefix('team')->middleware($sanctum)->name('team.')->group(function () {
    Route::get('', [TeamController::class, 'fetch'])->name('fetch');
    Route::post('', [TeamController::class, 'create'])->name('create');
    Route::post('update/{id}', [TeamController::class, 'update'])->name('update');
    Route::delete('{id}', [TeamController::class, 'destroy'])->name('delete');
});

// Role API
Route::prefix('role')->middleware($sanctum)->name('role.')->group(function () {
    Route::get('', [RoleController::class, 'fetch'])->name('fetch');
    Route::post('', [RoleController::class, 'create'])->name('create');
    Route::post('update/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('{id}', [RoleController::class, 'destroy'])->name('delete');
});

// Responsibility API
Route::prefix('responsibility')->middleware($sanctum)->name('responsibility.')->group(
    function () {
        Route::get('', [ResponsibilityController::class, 'fetch'])->name('fetch');
        Route::post('', [ResponsibilityController::class, 'create'])->name('create');
        Route::delete(
            '{id}',
            [ResponsibilityController::class, 'destroy']
        )->name('delete');
    }
);

// Employee API
Route::prefix('employee')->middleware($sanctum)->name('employee.')->group(function () {
    Route::get('', [EmployeeController::class, 'fetch'])->name('fetch');
    Route::post('', [EmployeeController::class, 'create'])->name('create');
    Route::post('update/{id}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('{id}', [EmployeeController::class, 'destroy'])->name('delete');
});
