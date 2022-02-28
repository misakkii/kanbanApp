<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProjectController,
    TaskController,
    DashboardController,
    DetailController,
};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Top', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function() {

    Route::post('/assign/off', [DashboardController::class, 'assignOff']);
    Route::post('task/execute', [DashboardController::class, 'executeTask']);
    Route::post('task/suspend', [DashboardController::class, 'suspendTask']);
    Route::post('task/complete', [DashboardController::class, 'completeTheTask']);

    //ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/dashboard/update', [DashboardController::class, 'update']);
    Route::post('/dashboard/chengeToToday', [DashboardController::class, 'chengeToToday']);

    //詳細
    Route::get('/detail', [DetailController::Class, 'index'])->name('detail');
    Route::put('/detail/{time}', [DetailController::Class, 'update'])->name('detail.update');
    Route::get('/detail/{id}/edit', [DetailController::class, 'edit']);

    //プロジェクト
    Route::get('/project/list', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/project/list/completion', [ProjectController::class, 'createCompletion'])->name('project.create.completion');
    Route::get('/project/list/delete', [ProjectController::class, 'createDelete'])->name('project.create.delete');

    Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store');
    Route::post('/project/update', [ProjectController::class, 'update'])->name('project.update');
    Route::post('/project/destroy', [ProjectController::class, 'destroy'])->name('project.destroy');

    //タスク
    Route::get('/task/list', [TaskController::class, 'index'])->name('task.index');
    Route::get('/task/list/completed', [TaskController::class, 'createCompletedList'])->name('task.create.completion');
    Route::get('/task/list/deleted', [TaskController::class, 'createDeletedList'])->name('task.create.delete');

    Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/update', [TaskController::class, 'update'])->name('task.update');
    Route::post('/task/destroy', [TaskController::class, 'destroy'])->name('task.destroy');

});
