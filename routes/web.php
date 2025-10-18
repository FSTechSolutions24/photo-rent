<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;

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

    // Folders (nested under gallery)
    Route::resource('folders', FolderController::class);

    // Photos (nested under gallery)
    Route::post('galleries/{gallery}/photos', [MediaController::class, 'store'])->name('photos.store');

});

Route::middleware(['auth', 'photographer'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
});

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::resource('profile', ProfileController::class);
});


Route::domain('{photographer_subdomain}.' . env('APP_DOMAIN'))->group(function () {
    Route::match(['get', 'post'], '/{client_name}/{gallery_slug}', [GalleryController::class, 'show'])->name('gallery.show');
});


require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
