<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard.index');

Route::get('/admin/users', [UserController::class, 'index'])->middleware(['auth', 'role:admin'])->name('admin.users.index');

Route::get('/admin/pages', function () {
    return view('admin.pages.index');
})->middleware(['auth', 'role:admin'])->name('admin.pages.index');

Route::get('/admin/reports', function () {
    return view('admin.reports.index');
})->middleware(['auth', 'role:admin'])->name('admin.reports.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
