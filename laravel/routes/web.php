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
use App\Http\Controllers\ServiceLogController;
//use App\Http\Controllers\RadarController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

//
//jw:test route to display X-Forwarded headers
//
//use Illuminate\Http\Request;
//Route::get('/test-proxy-headers', function (Request $request) {
//	return response()->json([
//	'X-Forwarded-For' => $request->header('X-Forwarded-For'),
//	'X-Forwarded-Host' => $request->header('X-Forwarded-Host'),
//	'X-Forwarded-Port' => $request->header('X-Forwarded-Port'),
//	'X-Forwarded-Proto' => $request->header('X-Forwarded-Proto'),
//	'All Received Headers' => $request->headers->all(),
//	]);
//});
//
//jw:test test '500' error page
//Route::get('/test-500', function() {
//	abort(500, 'Simulated Internal Server Error');
//});


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
	'datafile' => \App\Models\Datafile::whereIn('dataset_id', 
									\App\Models\Dataset::whereIn('database_id', 
										\App\Models\Database::where('databases.visible', '=', 1)->pluck('id')
										)->pluck('id')
									)->orderBy('updated_at', 'desc')->first(), 
	'tool' => \App\Models\Tool::orderBy('updated_at', 'desc')->first()]
); })->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SONICOM
/// RADAR
//Route::get('/radar', [RadarController::class, 'index'])->name('radar');

/// ABOUT etc
Route::view('/about', 'pages.about')->name('about');
Route::view('/scenarios', 'pages.scenarios')->name('scenarios');
Route::view('/challenges', 'pages.challenges')->name('challenges');
Route::view('/terms-of-use', 'pages.terms-of-use')->name('terms-of-use');

////////////////////////////////////////////////////////////////////////////////
// DATABASE
////////////////////////////////////////////////////////////////////////////////
//jw:note If you specify 'database' where the id is in the URL, then it will be used to get model data for the controller method
//jw:note 'implicit binding' https://laravel.com/docs/11.x/routing#implicit-binding

// All CRUD routes are protected in the controller __construct() function by the $this->authorizeResource(Database::class, 'database'); function
Route::resource('databases', DatabaseController::class);

// Grouping all non CRUD routes so we don't need to specify /database/{database} for every route
Route::prefix('databases/{database}')->group(function () {

	// admin routes
	Route::middleware(['auth', 'admin'])->group(function () {
		Route::get('/datathek', [DatabaseController::class, 'datathek'])->name('databases.datathek'); // display Datathek status and actions for admin testing
		Route::get('/services', [DatabaseController::class, 'services'])->name('databases.services'); // display Services status and actions for admin testing
		Route::get('/servicelog/removejob', [ServiceLogController::class, 'removejob'])->name('servicelog.removejob');
	});

	// owner routes ('own' policy)
	Route::middleware(['auth', 'can:own,database'])->group(function () { 
		Route::get('/update', [DatabaseController::class, 'upload'])->name('databases.upload'); // bulk upload
		Route::get('/subjectareas', [SubjectAreaController::class, 'index'])->name('databases.subjectareas');
		Route::get('/rightsholders', [RightsholderController::class, 'index'])->name('databases.rightsholders');
		Route::get('/rightsholders/up', [RightsholderController::class, 'up'])->name('rightsholders.up');
		Route::get('/rightsholders/down', [RightsholderController::class, 'down'])->name('rightsholders.down');
		Route::get('/keywords', [KeywordController::class, 'index'])->name('databases.keywords');
		Route::get('/keywords/up', [KeywordController::class, 'up'])->name('keywords.up');
		Route::get('/keywords/down', [KeywordController::class, 'down'])->name('keywords.down');
		Route::get('/relatedidentifiers', [RelatedIdentifierController::class, 'index'])->name('databases.relatedidentifiers');
		Route::get('/creators', [CreatorController::class, 'index'])->name('databases.creators');
		Route::get('/creators/up', [CreatorController::class, 'up'])->name('creators.up');
		Route::get('/creators/down', [CreatorController::class, 'down'])->name('creators.down');
		Route::get('/publishers', [PublisherController::class, 'index'])->name('databases.publishers');
		Route::get('/publishers/up', [PublisherController::class, 'up'])->name('publishers.up');
		Route::get('/publishers/down', [PublisherController::class, 'down'])->name('publishers.down');
		Route::get('/purge', [DatabaseController::class, 'purge'])->name('databases.purge');
		Route::get('/relatedidentifiers/up', [RelatedIdentifierController::class, 'up'])->name('relatedidentifiers.up');
		Route::get('/relatedidentifiers/down', [RelatedIdentifierController::class, 'down'])->name('relatedidentifiers.down');
		Route::get('/visibility', [DatabaseController::class, 'visibility'])->name('databases.visibility');
		Route::get('/datasetdefs/up', [DatabaseController::class, 'datasetdefup'])->name('datasetdefs.up');
		Route::get('/datasetdefs/down', [DatabaseController::class, 'datasetdefdown'])->name('datasetdefs.down');
		Route::get('/duplicate', [DatabaseController::class, 'duplicate'])->name('databases.duplicate');
	});

	// auth routes (logged in users)
	Route::middleware(['auth'])->group(function () {
		Route::get('/comments', [CommentController::class, 'index'])->name('databases.comments');
	});

	// public routes ('view' policy protects hidden databases)
	Route::middleware(['auth', 'can:view,database'])->group(function () {
		Route::get('/download', [DatabaseController::class, 'download'])->name('databases.download');
		Route::get('/datasetdefs', [DatabaseController::class, 'datasetdefs'])->name('databases.datasetdefs');
		Route::get('/showdatasets', [DatabaseController::class, 'showdatasets'])->name('databases.showdatasets');
	});
});

////////////////////////////////////////////////////////////////////////////////
// DATASET
////////////////////////////////////////////////////////////////////////////////
Route::resource('datasets', DatasetController::class);
Route::resource('databases.datasets', DatasetController::class); // /databases/{{database}}/datasets (https://davecalnan.blog/laravel-routing-gotchas)
Route::post('/datasets/{dataset}/uploadtoradar', [DatasetController::class, 'uploadtoradar'])->name('datasets.uploadtoradar');
Route::delete('/datasets/{dataset}/deletefromradar', [DatasetController::class, 'deletefromradar'])->name('datasets.deletefromradar');
////////////////////////////////////////////////////////////////////////////////
// DATAFILES
////////////////////////////////////////////////////////////////////////////////
Route::resource('datafiles', DatafileController::class);
Route::post('/datafiles/{datafile}/uploadtoradar', [DatafileController::class, 'uploadtoradar'])->name('datafiles.uploadtoradar');
Route::delete('/datafiles/{datafile}/deletefromradar', [DatafileController::class, 'deletefromradar'])->name('datafiles.deletefromradar');
Route::post('/datafiles/{datafile}/rerunservice', [DatafileController::class, 'rerunService'])->name('datafiles.rerunservice')->middleware('auth', 'role:admin');
////////////////////////////////////////////////////////////////////////////////
// DATASETDEFS
////////////////////////////////////////////////////////////////////////////////
Route::resource('datasetdefs', DatasetdefController::class);
Route::get('datasetdefs/{datasetdef}/duplicate', [DatasetdefController::class, 'duplicate'])->name('datasetdefs.duplicate');
Route::post('datasetdefs/{datasetdef}/rerunservice', [DatasetdefController::class, 'rerunService'])->name('datasetdefs.rerunservice')->middleware('auth', 'role:admin');
////////////////////////////////////////////////////////////////////////////////
// CREATORS
////////////////////////////////////////////////////////////////////////////////
Route::resource('creators', CreatorController::class);
////////////////////////////////////////////////////////////////////////////////
// Publishers
////////////////////////////////////////////////////////////////////////////////
Route::resource('publishers', PublisherController::class);
////////////////////////////////////////////////////////////////////////////////
// SubjectAreas
////////////////////////////////////////////////////////////////////////////////
Route::resource('subjectareas', SubjectAreaController::class);
////////////////////////////////////////////////////////////////////////////////
// Rightsholders
////////////////////////////////////////////////////////////////////////////////
Route::resource('rightsholders', RightsholderController::class);
////////////////////////////////////////////////////////////////////////////////
// Keywords
////////////////////////////////////////////////////////////////////////////////
Route::resource('keywords', KeywordController::class);
////////////////////////////////////////////////////////////////////////////////
// RelatedIdentifiers
////////////////////////////////////////////////////////////////////////////////
Route::resource('relatedidentifiers', RelatedIdentifierController::class);
////////////////////////////////////////////////////////////////////////////////
// Comments
////////////////////////////////////////////////////////////////////////////////
Route::resource('comments', CommentController::class)->only('index','create','edit','update','destroy');
////////////////////////////////////////////////////////////////////////////////
/// WIDGETs
////////////////////////////////////////////////////////////////////////////////
Route::resource('widgets', WidgetController::class);
////////////////////////////////////////////////////////////////////////////////
/// TOOLS
////////////////////////////////////////////////////////////////////////////////
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
Route::get('/tools/{tool}/datathek', [ToolController::class, 'datathek'])->name('tools.datathek')->middleware('admin'); // display Datathek status and actions for admin testing
Route::get('/tools/{tool}/duplicate', [ToolController::class, 'duplicate'])->name('tools.duplicate');
////////////////////////////////////////////////////////////////////////////////
/// SERVICEs
////////////////////////////////////////////////////////////////////////////////
Route::resource('services', ServiceController::class);
////////////////////////////////////////////////////////////////////////////////
/// PROFILE
////////////////////////////////////////////////////////////////////////////////
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'process'])->name('profile.process');
    Route::get('/profile/callback', [ProfileController::class, 'callback'])->name('profile.callback');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
