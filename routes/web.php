<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherCourseGroupController;
use Illuminate\Support\Facades\Route;

// Доступ для всех авторизованных пользователей
Route::middleware('auth')->group(function () {

    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/confirm', [AuthController::class, 'showConfirm'])->name('confirm');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Маршруты для студентов
    Route::prefix('student')->group(function () {
        Route::get('/',     [StudentController::class, 'showMain'])->name('student.main');
    });
    // Маршруты для администраторов
    Route::prefix('admin')->group(function () {
        Route::get('/generate', [AdminController::class, 'showGenerate'])->name('admin.generate');
        Route::post('/generate', [AdminController::class, 'createUser'])->name('admin.generate.store');
        Route::get('/list', [AdminController::class, 'showList'])->name('admin.list');
        Route::get('/groups', [AdminController::class, 'showGroups'])->name('admin.groups');
        Route::get('/add/group', [AdminController::class, 'showAddGroup'])->name('admin.add.group');
        Route::post('/add/group', [AdminController::class, 'storeGroup'])->name('admin.store.group');
        Route::put('/group/update/{id}', [AdminController::class, 'updateGroup'])->name('admin.group.update');
        Route::post('/group/delete/{id}', [AdminController::class, 'deleteGroup'])->name('admin.group.delete');
    });
});
