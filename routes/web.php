<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
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

Route::get('/user', function () {
    return view('user.index');
})->middleware(['auth', 'role:user']);

Route::get('/reports', function () {
    return view('user.reports');
})->middleware(['auth', 'role:user'])->name('user.reports');

Route::get('/projects', function () {
    return view('user.projects');
})->middleware(['auth', 'role:user'])->name('user.projects');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'role:admin'])->name('admin.index');




// Route::get('/dashboard', [SettingController::class, 'dynamicSetting'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/setting/{group}', [SettingController::class, 'setting'])->middleware(['auth', 'role:admin', 'check_group_existence'])->name('admin.setting.general');


Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard.index');

Route::get('/admin/users', [UserController::class, 'index'])->middleware(['auth', 'role:admin'])->name('admin.users.index');

Route::post('/save-settings/{group}', [SettingController::class, 'saveSettings'])->name('save-settings');

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
