<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\FinanceReportController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\WhatsAppTemplateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;

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

Route::middleware(['auth', 'photographer'])->prefix('photographer')->name('photographer.')->group(function () {
    Route::put('profile/update', [ProfileController::class, 'profile_update'])->name('profile.update');
    Route::get('profile/settings', [ProfileController::class, 'profile_settings'])->name('profile.settings');
    Route::get('calendar/preview', [ProfileController::class, 'calendar'])->name('my.calendar');

    Route::get('appointments/data', [AppointmentController::class, 'getData'])->name('appointments.data');
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);
});

Route::middleware(['auth', 'photographer'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('portfolio', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('portfolio', [PortfolioController::class, 'update'])->name('portfolio.update');
    // Clients
    Route::get('clients/data', [ClientController::class, 'getData'])->name('clients.data');
    Route::resource('clients', ClientController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    // Galleries (nested under client)
    Route::post('folders/download', [FolderController::class, 'download'])->name('folders.download');
    Route::post('galleries/download', [GalleryController::class, 'download'])->name('galleries.download');
    Route::get('galleries/data', [GalleryController::class, 'getData'])->name('galleries.data');
    Route::get('galleries/{gallery}/whatsapp', [GalleryController::class, 'sendViaWhatsApp'])->name('galleries.whatsapp');
    Route::resource('galleries', GalleryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);

    Route::get('whatsapp-template', [WhatsAppTemplateController::class, 'edit'])->name('whatsapp-template.edit');
    Route::put('whatsapp-template', [WhatsAppTemplateController::class, 'update'])->name('whatsapp-template.update');
    
    // Session
    Route::get('sessions/data', [SessionController::class, 'getData'])->name('sessions.data');
    Route::resource('sessions', SessionController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);

    // Finance report
    Route::get('finance-report', [FinanceReportController::class, 'index'])->name('finance-report.index');

    // Folders (nested under gallery)
    Route::post('/api/galleries/{gallery}/folders/{folder}/media', [FolderController::class, 'listJsonMedia'])->name('api.galleries.folders.media');
    Route::post('/galleries/{gallery}/folders/{folder}/upload', [FolderController::class, 'upload'])->name('galleries.folders.upload');
    Route::post('/galleries/{gallery}/folders/{folder}/download', [FolderController::class, 'download'])->name('galleries.folders.download');
    Route::get('/api/galleries/{gallery}/folders', [FolderController::class, 'listJson'])->name('api.galleries.folders.index');
    Route::resource('galleries.folders', FolderController::class)
        ->only(['index', 'store', 'destroy']);
    
    
    // Photos (nested under gallery)
    Route::post('galleries/{gallery}/photos', [MediaController::class, 'store'])->name('photos.store');        
    Route::delete('media/{gallery}/delete',[MediaController::class, 'destroy'])->name('media.destroy');
    Route::patch('media/{gallery}/privacy',[MediaController::class, 'updatePrivacy'])->name('media.privacy');
    Route::post('media/{gallery}/download',[MediaController::class, 'download'])->name('media.download');
    Route::post('media/{gallery}/download_folder',[MediaController::class, 'download_folder'])->name('media.download_folder');

});

Route::middleware(['auth', 'photographer'])->group(function () {
    // Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // These fixed paths must come before the resource wildcard: profile/{profile}.
    Route::get('/profile/inactive', [ProfileController::class, 'inactivephotographer'])->name('profile.inactive');
    Route::post('/profile/renew-subscription', [ProfileController::class, 'renewSubscription'])->name('profile.renew-subscription');
    Route::get('/api/profile/checksubdomain', [ProfileController::class, 'checksubdomain'])->name('checksubdomain');
    Route::post('/api/profile/createphotographerprofile', [ProfileController::class, 'createphotographerprofile'])
        ->middleware('throttle:5,1')
        ->name('createphotographerprofile');
    Route::resource('profile', ProfileController::class);
});

Route::get('paymobcallbackresponseview', [ProfileController::class, 'paymobcallbackresponseview'])->name('paymob.paymobcallbackresponseview');
Route::post('paymobcallback', [ProfileController::class, 'callback'])
    ->middleware('throttle:30,1')
    ->name('paymob.callback');


Route::domain('{photographer_subdomain}.' . env('APP_DOMAIN'))->group(function () {
    // Public portfolio for a photographer's subdomain, e.g. pola.localhost:8000/portfolio.
    Route::get('/portfolio', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::post('/{gallery_slug}/download', [GalleryController::class, 'requestDownload'])
        ->middleware('throttle:5,1')
        ->name('gallery.download');
    Route::match(['get', 'post'], '/{gallery_slug}', [GalleryController::class, 'show'])
        ->middleware('throttle:20,1')
        ->name('gallery.show');
});



Route::middleware(['superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('plans/data', [SubscriptionPlanController::class, 'getData'])->name('plans.data');
    Route::resource('plans', SubscriptionPlanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update']);
});


require __DIR__.'/auth.php';

