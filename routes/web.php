<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ ProjectController };
use Inertia\Inertia;

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
    Route::get('/dashboard', function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/user/task', function() {
        return Inertia::render('Task');
    })->name('user.task');

    Route::get('/project', [ProjectController::class, 'index'])->name('project.index');
    Route::get('/project/completion', [ProjectController::class, 'createCompletion'])->name('project.create.completion');
    Route::get('/project/delete', [ProjectController::class, 'createDelete'])->name('project.create.delete');

    Route::post('/project/store', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/project/edit', [ProjectController::class, 'edit'])->name('project.edit');
    Route::post('/project/update', [ProjectController::class, 'update'])->name('project.update');
    Route::post('/project/destroy', [ProjectController::class, 'delete'])->name('project.destroy');
});
