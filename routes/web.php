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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:user'])->group(function () {
    

    Route::get('/user', function () {
        return view('user.index');
    })->name('user.index');

    Route::get('/reports', function () {
        return view('user.reports');
    })->name('user.reports');

    Route::get('/projects', function () {
        return view('user.projects');
    })->name('user.projects');
    Route::get('/pages/{slug}', [SettingController::class, 'pageShow']);

});





Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', function () { 
        return view('admin.index');
    })->name('admin.index');

    Route::get('/setting/{group}', [SettingController::class, 'setting'])
    ->middleware('check_group_existence')->name('admin.setting.general');

    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard.index');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');

    Route::post('/save-settings/{group}', [SettingController::class, 'saveSettings'])->name('save-settings');

    Route::get('/pages', [SettingController::class, 'index'])->name('admin.pages.index');
    Route::get('/page/{id}', [SettingController::class, 'show'])->name('admin.pages.edit');
    Route::patch('/page/edit/{id}', [SettingController::class, 'update'])->name('admin.page.update');
    Route::get('/pages/{slug}',[SettingController::class,'pageShow'])->name('admin.page');

    Route::get('/reports', function () {
        return view('admin.reports.index');
    })->name('admin.reports.index');


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
