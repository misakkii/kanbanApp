<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DetailController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('get/work-time', [DetailController::Class, 'workTimesIndex']);
    Route::post('work-time/{id}/destroy', [DetailController::Class, 'destroy']);

    Route::post('detail/update', [DetailController::Class, 'update']);

});
// Route::get('/user', function(Request $request) {
//     return $request->user();
// });
