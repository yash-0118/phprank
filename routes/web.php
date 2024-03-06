<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ImpersonateController;
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
Route::middleware(['disable_btn'])->group(
    function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');

        Route::get('/report/{id}', [DataController::class, 'report'])->middleware('middleware_for_private');

        Route::get('/validate/{id}', [DashboardController::class, 'password'])->name('password.entry.form');
        Route::post('/verify-password/{id}', [DashboardController::class, 'verifyPassword'])->name('verify.password');
        Route::get('/report-public{id}', [DashboardController::class, 'publicReport'])->name('public.report');
        Route::get('/password-report/{id}', [DashboardController::class, 'passwordReport'])->name('password.report');

        Route::middleware(['auth', 'role:user', 'session'])->group(function () {

            Route::post('/stop-impersonating', [ImpersonateController::class, 'stopImpersonating'])->name('stop.impersonating');

            Route::get('/user', [DashboardController::class, 'index'])->name('user.index');

            Route::get('/reports', [DataController::class, 'index'])->name('user.reports');
            Route::post("/report/save", [DataController::class, 'search'])->name('user.report.save');
            Route::get('/reports/{id}/edit', [DataController::class, 'edit'])->name('user.report.edit');
            Route::patch('/reports/{id}/update', [DataController::class, 'update'])->name('user.report.update');
            Route::delete('/report/{id}/delete', [DataController::class, 'destroy'])->name('user.report.delete');

            Route::get('/projects', [DataController::class, 'projects'])->name('user.projects');
            Route::get('project/{domain}', [dataController::class, 'reports']);
            Route::get('/pages/{slug}', [SettingController::class, 'pageShow']);
        });

        Route::prefix('admin')->middleware(['auth', 'role:admin','session'])->group(function () {
            Route::impersonate();

            //Setting Routes
            Route::get('/setting/{group}', [SettingController::class, 'setting'])
                ->middleware('check_group_existence')->name('admin.setting.general');
            Route::post('/save-settings/{group}', [SettingController::class, 'saveSettings'])->name('save-settings');

            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard.index');

            //Users Routes
            Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
            Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
            Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::patch('user/{id}/update', [UserController::class, 'update'])->name('admin.user.update');

            //Pages Routes
            Route::get('/pages', [SettingController::class, 'index'])->name('admin.pages.index');
            Route::get('/page/{id}', [SettingController::class, 'show'])->name('admin.pages.edit');
            Route::patch('/page/edit/{id}', [SettingController::class, 'update'])->name('admin.page.update');
            Route::get('/pages/{slug}', [SettingController::class, 'pageShow'])->name('admin.page');
            Route::get('/page/create/new', [SettingController::class, 'create'])->name('admin.pages.create');
            Route::post('/pages/store/new', [SettingController::class, 'store'])->name('admin.pages.store');
            Route::delete('/page/destroy/{id}', [SettingController::class, 'destroy'])->name('admin.pages.destroy');

            //Reports Routes
            Route::get('/reports', [DashboardController::class, 'reports'])->name('admin.reports.index');
            Route::get('/reports/{id}/edit', [DashboardController::class, 'edit'])->name('admin.reports.edit');
            Route::patch('/reports/{id}/update', [DashboardController::class, 'update'])->name('admin.reports.update');
            Route::delete('/report/{id}/delete', [DashboardController::class, 'destroy'])->name('admin.report.delete');

            Route::get('/start-impersonate', [ImpersonateController::class, 'startImpersonate'])->name('start.impersonate');
            Route::post('/start-impersonate/{user}', [ImpersonateController::class, 'impersonateUser'])->name('impersonate.user');
        });
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
