<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicrositesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')
    ->resource('microsites', MicrositesController::class);

Route::middleware('auth')->group(function () {
    Route::get('/role-user', [RolePermissionController::class, 'index'])->name('rolePermission.index');
    Route::put('/role-user/{user}/update', [RolePermissionController::class, 'update'])->name('admin.users.update');
    Route::get('/role-permission', [RolePermissionController::class, 'managePermissions'])->name('rolePermission.permissions');
    Route::put('/roles/{role}/update-permissions', [RolePermissionController::class, 'editPermissions'])->name('admin.rolePermission.edit-permissions');
});

Route::middleware('auth')
    ->resource('users', UserController::class);

require __DIR__ . '/auth.php';
