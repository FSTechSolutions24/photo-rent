<?php

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SubscriptionPlanController;

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
    return view('welcome');
});





Route::middleware(['auth', 'photographer'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Clients
    Route::get('clients/data', [ClientController::class, 'getData'])->name('clients.data');
    Route::resource('clients', ClientController::class);

    // Galleries (nested under client)
    Route::get('galleries/data', [GalleryController::class, 'getData'])->name('galleries.data');
    Route::resource('galleries', GalleryController::class);
    
    // Session
    Route::get('sessions/data', [SessionController::class, 'getData'])->name('sessions.data');
    Route::resource('sessions', SessionController::class);

    // Folders (nested under gallery)
    Route::post('/api/galleries/{gallery}/folders/{folder}/media', [FolderController::class, 'listJsonMedia'])->name('api.galleries.folders.media');
    Route::post('/galleries/{gallery}/folders/{folder}/upload', [FolderController::class, 'upload'])->name('galleries.folders.upload');
    Route::get('/api/galleries/{gallery}/folders', [FolderController::class, 'listJson'])->name('api.galleries.folders.index');
    Route::resource('galleries.folders', FolderController::class);
    
    
    // Photos (nested under gallery)
    Route::post('galleries/{gallery}/photos', [MediaController::class, 'store'])->name('photos.store');        
    Route::delete('media/{gallery}/delete',[MediaController::class, 'destroy'])->name('media.destroy');

});

Route::middleware(['auth', 'photographer'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
});

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('profile', ProfileController::class);
    Route::get('/api/profile/checksubdomain', [ProfileController::class, 'checksubdomain'])->name('checksubdomain');
    Route::get('/api/profile/createphotographerprofile', [ProfileController::class, 'createphotographerprofile'])->name('createphotographerprofile');
});


Route::domain('{photographer_subdomain}.' . env('APP_DOMAIN'))->group(function () {
    Route::match(['get', 'post'], '/{client_name}/{gallery_slug}', [GalleryController::class, 'show'])->name('gallery.show');
});



Route::middleware(['superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('plans/data', [SubscriptionPlanController::class, 'getData'])->name('plans.data');
    Route::resource('plans', SubscriptionPlanController::class);
});


require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
