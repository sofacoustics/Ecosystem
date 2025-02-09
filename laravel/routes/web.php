<?php

use App\Http\Controllers\WidgetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SubjectAreaController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DatafileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DatasetdefController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadarController;
use App\Http\Controllers\ToolController;
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

Route::get('/', function () { return view('landing'); })->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SONICOM
/// RADAR
Route::get('/radar', [RadarController::class, 'index'])->name('radar');
Route::get('/radar/dataset/{id}', [RadarController::class, 'dataset']);
Route::get('/radar/get/{id}', [RadarController::class, 'get']);

/// ABOUT etc
Route::view('/about', 'pages.about')->name('about');
Route::view('/scenarios', 'pages.scenarios')->name('scenarios');
Route::view('/challenges', 'pages.challenges')->name('challenges');
Route::view('/terms-of-use', 'pages.terms-of-use')->name('terms-of-use');

// DATABASE (auth specified in __construct())
//
//jw:note If you specify 'database' where the id is in the URL, then it will be used to get model data for the controller method
//jw:note 'implicit binding' https://laravel.com/docs/11.x/routing#implicit-binding
Route::resource('databases', DatabaseController::class);
Route::get('/databases/{database}/radar', [DatabaseController::class, 'radarShow'])->name('databases.radar');
Route::get('/databases/{database}/radar/edit', [DatabaseController::class, 'radarEdit'])->name('databases.radar.edit');
Route::get('/databases/{database}/datasetdefs', [DatabaseController::class, 'datasetdefs'])->name('databases.datasetdefs');
Route::get('/databases/{database}/creators', [CreatorController::class, 'index'])->name('databases.creators');
Route::get('/databases/{database}/publishers', [PublisherController::class, 'index'])->name('databases.publishers');
Route::get('/databases/{database}/subjectareas', [SubjectAreaController::class, 'index'])->name('databases.subjectareas');

// DATASET
Route::resource('datasets', DatasetController::class);
Route::resource('databases.datasets', DatasetController::class); // /databases/{{database}}/datasets (https://davecalnan.blog/laravel-routing-gotchas)
// DATAFILES
Route::resource('datafiles', DatafileController::class);
// DATASETDEFS
Route::resource('datasetdefs', DatasetdefController::class);

// CREATORS
Route::resource('creators', CreatorController::class);
// Publishers
Route::resource('publishers', PublisherController::class);
// SubjectAreas
Route::resource('subjectareas', SubjectAreaController::class);
/// WIDGETs
Route::resource('widgets', WidgetController::class);
/// TOOLS
Route::resource('tools', ToolController::class);


/// Backend (Filament)
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
