<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\DatabaseCollection;
use App\Http\Resources\DatabaseResource;
use App\Models\Database;

use App\Http\Controllers\Api\Radar\DatasetController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/databases', function () {
    return new DatabaseCollection(Database::all());
});
Route::get('/databases/{id}', function (string $id) {
    return new DatabaseResource(Database::findOrFail($id));
});


////////////////////////////////////////////////////////////////////////////////
// RADAR API
////////////////////////////////////////////////////////////////////////////////
//  /datasets
Route::get('/radar/datasets', [ App\Http\Controllers\Api\Radar\DatasetController::class, 'index' ]);
Route::get('/radar/datasets/{id}', [ App\Http\Controllers\Api\Radar\DatasetController::class, 'show' ]);
//Route::post('/radar/datasets/{id}', [ App\Http\Controllers\Api\Radar\DatasetController::class, 'testupdate' ]);
