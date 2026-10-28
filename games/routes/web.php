<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/geheim', function () {
    return view('geheim');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin|klant'])->group(function () {
    Route::get('games', [GameController::class, 'index']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('games/show/{id}', [GameController::class, 'show']);
    Route::get('games/create', [GameController::class, 'create']);
    Route::post('games/store', [GameController::class, 'store']);
    Route::get('games/edit/{id}', [GameController::class, 'edit']);
    Route::post('games/update/{id}', [GameController::class, 'update']);
    Route::post('games/destroy/{id}', [GameController::class, 'destroy']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::get('role-permissions', [RolePermissionController::class, 'index'])->name('role-permissions.index');
        Route::put('role-permissions/{role}', [RolePermissionController::class, 'update'])->name('role-permissions.update');

        Route::get('user-roles', [UserRoleController::class, 'index'])->name('user-roles.index');
        Route::put('user-roles/{user}', [UserRoleController::class, 'update'])->name('user-roles.update');
    });
});

require __DIR__.'/auth.php';
