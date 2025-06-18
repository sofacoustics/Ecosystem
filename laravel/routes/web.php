<?php

use App\Http\Controllers\WidgetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SubjectAreaController;
use App\Http\Controllers\RightsholderController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\RelatedIdentifierController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DatafileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DatasetdefController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RadarController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

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

Route::get('/', function () { return view('landing', [
	'database' => \App\Models\Database::where('databases.visible', '=', 1)->orderBy('updated_at', 'desc')->first(),
	'datafile' => \App\Models\Datafile::orderBy('updated_at', 'desc')->first(), 
	'tool' => \App\Models\Tool::orderBy('updated_at', 'desc')->first()]
); })->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SONICOM
/// RADAR
Route::get('/radar', [RadarController::class, 'index'])->name('radar');

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
Route::get('/databases/{database}/visibility', [DatabaseController::class, 'visibility'])->name('databases.visibility');
//Route::get('/databases/{database}/radar', [DatabaseController::class, 'radarShow'])->name('databases.radar');
Route::get('/databases/{database}/datathek', [DatabaseController::class, 'datathek'])->name('databases.datathek')->middleware('admin'); // display Datathek status and actions for admin testing
//Route::get('/databases/{database}/radar/edit', [DatabaseController::class, 'radarEdit'])->name('databases.radar.edit');
Route::get('/databases/{database}/datasetdefs', [DatabaseController::class, 'datasetdefs'])->name('databases.datasetdefs');
Route::get('/databases/{database}/creators', [CreatorController::class, 'index'])->name('databases.creators');
Route::get('/databases/{database}/publishers', [PublisherController::class, 'index'])->name('databases.publishers');
Route::get('/databases/{database}/subjectareas', [SubjectAreaController::class, 'index'])->name('databases.subjectareas');
Route::get('/databases/{database}/rightsholders', [RightsholderController::class, 'index'])->name('databases.rightsholders');
Route::get('/databases/{database}/keywords', [KeywordController::class, 'index'])->name('databases.keywords');
Route::get('/databases/{database}/relatedidentifiers', [RelatedIdentifierController::class, 'index'])->name('databases.relatedidentifiers');
Route::get('/databases/{database}/update', [DatabaseController::class, 'upload'])->name('databases.upload');
Route::get('/databases/{database}/download', [DatabaseController::class, 'download'])->name('databases.download');
Route::get('/databases/{database}/purge', [DatabaseController::class, 'purge'])->name('databases.purge');
Route::get('/databases/{database}/showdatasets', [DatabaseController::class, 'showdatasets'])->name('databases.showdatasets');
Route::get('/databases/{database}/comments', [CommentController::class, 'index'])->name('databases.comments');
Route::get('/databases/{database}/datasets/bulkupload', [DatasetController::class, 'bulkupload'])->name('databases.datasets.bulkupload');

// DATASET
Route::resource('datasets', DatasetController::class);
Route::resource('databases.datasets', DatasetController::class); // /databases/{{database}}/datasets (https://davecalnan.blog/laravel-routing-gotchas)
Route::post('/datasets/{dataset}/uploadtoradar', [DatasetController::class, 'uploadtoradar'])->name('datasets.uploadtoradar');
Route::delete('/datasets/{dataset}/deletefromradar', [DatasetController::class, 'deletefromradar'])->name('datasets.deletefromradar');
// DATAFILES
Route::resource('datafiles', DatafileController::class);
Route::post('/datafiles/{datafile}/uploadtoradar', [DatafileController::class, 'uploadtoradar'])->name('datafiles.uploadtoradar');
Route::delete('/datafiles/{datafile}/deletefromradar', [DatafileController::class, 'deletefromradar'])->name('datafiles.deletefromradar');
// DATASETDEFS
Route::resource('datasetdefs', DatasetdefController::class);

// CREATORS
Route::resource('creators', CreatorController::class);
// Publishers
Route::resource('publishers', PublisherController::class);
// SubjectAreas
Route::resource('subjectareas', SubjectAreaController::class);
// Rightsholders
Route::resource('rightsholders', RightsholderController::class);
// Keywords
Route::resource('keywords', KeywordController::class);
// Keywords
Route::resource('relatedidentifiers', RelatedIdentifierController::class);
// Comments
Route::resource('comments', CommentController::class);

/// WIDGETs
Route::resource('widgets', WidgetController::class);
/// TOOLS
Route::resource('tools', ToolController::class);
Route::get('/tools/{tool}/comments', [CommentController::class, 'index'])->name('tools.comments');
Route::get('/tools/{tool}/upload', [ToolController::class, 'upload'])->name('tools.upload');
Route::get('/tools/{tool}/doi', [ToolController::class, 'doi'])->name('tools.doi');
Route::get('/tools/{tool}/creators', [CreatorController::class, 'index'])->name('tools.creators');
Route::get('/tools/{tool}/publishers', [PublisherController::class, 'index'])->name('tools.publishers');
Route::get('/tools/{tool}/subjectareas', [SubjectAreaController::class, 'index'])->name('tools.subjectareas');
Route::get('/tools/{tool}/rightsholders', [RightsholderController::class, 'index'])->name('tools.rightsholders');
Route::get('/tools/{tool}/keywords', [KeywordController::class, 'index'])->name('tools.keywords');
Route::get('/tools/{tool}/relatedidentifiers', [RelatedIdentifierController::class, 'index'])->name('tools.relatedidentifiers');

/// SERVICEs
Route::resource('services', ServiceController::class);

/// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'process'])->name('profile.process');
    Route::get('/profile/callback', [ProfileController::class, 'callback'])->name('profile.callback');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
