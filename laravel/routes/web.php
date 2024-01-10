<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
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
/// ABOUT
Route::view('/about', 'pages.about')->name('about');
/*Route::get('about', function() {
	return view('pages.about');
});
*/
// DATABASE (auth specified in __construct())
Route::resource('databases', DatabaseController::class);
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

Route::resource('files', FileController::class);

/// ADMIN
Route::group(['middleware' => ['role:admin']], function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin'); // named route: https://laravel.com/docs/10.x/routing#named-routes
});

/// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
