<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DatafileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DatasetdefController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadarController;
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
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SONICOM
/// RADAR
Route::get('/radar', [RadarController::class, 'index']);
Route::get('/radar/dataset/{id}', [RadarController::class, 'dataset']);
Route::get('/radar/get/{id}', [RadarController::class, 'get']);

/// ABOUT
Route::view('/about', 'pages.about')->name('about');
Route::view('/terms-of-use', 'pages.terms-of-use')->name('terms-of-use');

/*Route::get('about', function() {
	return view('pages.about');
});
*/
//
// DATABASE (auth specified in __construct())
//
//jw:note If you specify 'database' where the id is in the URL, then it will be used to get model data for the controller method
//jw:note 'implicit binding' https://laravel.com/docs/11.x/routing#implicit-binding
Route::get('/databases/{database}/radar', [DatabaseController::class, 'radarShow'])->name('databases.radar');
Route::get('/databases/{database}/radar/edit', [DatabaseController::class, 'radarEdit'])->name('databases.radar.edit');
Route::get('/databases/{database}/datasetdefs', [DatabaseController::class, 'datasetdefs'])->name('databases.datasetdefs');
Route::resource('databases', DatabaseController::class);
//
// DATASET
//
Route::resource('datasets', DatasetController::class);
//Route::resource('databases.datasets', DatasetController::class);
//Route::middleware('auth')->group(function () {
//    Route::get('/database/create', [DatabaseController::class, 'create']);
//    Route::get('/database', [DatabaseController::class, 'edit'])->name('database.edit');
//});
//Route::resource('database.create', DatabaseController::class)->middleware('auth');
//Route::resource('database', DatabaseController::class)->middleware('auth')->only([
//    'edit', 'create'
//]);
// To nest the resource controllers, you may use "dot" notation in your route declaration (https://laravel.com/docs/10.x/controllers#restful-partial-resource-routes)
//Route::resource('database.edit', DatabaseController::class)->middleware('auth');
//Route::resource('/database/create', DatabaseController::class)->middleware('auth');
//Route::post('/database/create','ProjectController@store');
//
// DATAFILES
//
Route::resource('datafiles', DatafileController::class);
//
// DATASETDEFS
//
Route::resource('datasetdefs', DatasetdefController::class);

/// ADMIN
//jw:note currently using filament 'admin' panel
#Route::group(['middleware' => ['role:admin']], function() {
#    Route::get('/admin', [AdminController::class, 'index'])->name('admin'); // named route: https://laravel.com/docs/10.x/routing#named-routes
#});

/// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
