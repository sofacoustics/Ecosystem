<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\FileController;
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
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SONICOM
/// DATA
Route::resource('data', DataController::class)
	->only(['index']);
Route::view('/about', 'pages.about');
/*Route::get('about', function() {
	return view('pages.about');
});
*/
// DATASET (auth specified in __construct())
Route::resource('dataset', DatasetController::class);
//Route::middleware('auth')->group(function () {
//    Route::get('/dataset/create', [DatasetController::class, 'create']);
//    Route::get('/dataset', [DatasetController::class, 'edit'])->name('dataset.edit');
//});
//Route::resource('dataset.create', DatasetController::class)->middleware('auth');
//Route::resource('dataset', DatasetController::class)->middleware('auth')->only([
//    'edit', 'create'
//]);
// To nest the resource controllers, you may use "dot" notation in your route declaration (https://laravel.com/docs/10.x/controllers#restful-partial-resource-routes)
//Route::resource('dataset.edit', DatasetController::class)->middleware('auth');
//Route::resource('/dataset/create', DatasetController::class)->middleware('auth');
//Route::post('/dataset/create','ProjectController@store');

Route::resource('files', FileController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
