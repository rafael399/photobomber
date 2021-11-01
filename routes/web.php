<?php

use App\Http\Controllers\UploadPhotoController;
use App\Http\Controllers\ListPhotosController;
use App\Http\Controllers\CreateAlbumController;
use App\Http\Controllers\ListAlbumsController;
use App\Http\Controllers\GetAlbumController;
use App\Http\Controllers\UpdateAlbumController;
use App\Http\Controllers\GetAlbumPhotosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route to the 'Gallery' page
Route::get('/gallery', function () {
    return Inertia::render('Gallery');
})->middleware(['auth', 'verified'])->name('gallery');

// Post method to upload photo to gallery
Route::middleware('auth')->group(function () {
    Route::post('/galleryPhoto', UploadPhotoController::class);
});

// Get method to retrieve all photos the user have in gallery
Route::middleware('auth')->group(function () {
    Route::get('/galleryPhotos', ListPhotosController::class);
});

// Route to the 'CreateAlbum' page
Route::get('/create-album', function () {
    return Inertia::render('CreateAlbum');
})->middleware(['auth', 'verified'])->name('create-album');

// Post method to create a new album
Route::middleware('auth')->group(function () {
    Route::post('/album', CreateAlbumController::class)->name('album');
});

// Route to the 'EditAlbum' page
Route::get('/edit-album', function () {
    return Inertia::render('EditAlbum');
})->middleware(['auth', 'verified'])->name('edit-album');

// Put method to update an album
Route::middleware('auth')->group(function () {
    Route::put('/album', UpdateAlbumController::class)->name('update-album');
});

// Get method to retrieve all albums the user have
Route::middleware('auth')->group(function () {
    Route::get('/albums', ListAlbumsController::class);
});

// Get method to retrieve specific album
Route::middleware('auth')->group(function () {
    Route::get('/album/{albumId}', GetAlbumController::class);
});

// Get method to retrieve photos from specific album
Route::middleware('auth')->group(function () {
    Route::get('/album-photos/{albumId}', GetAlbumPhotosController::class);
});

// Route to the 'AlbumDashboard' page
Route::get('/album-dashboard', function () {
    return Inertia::render('AlbumDashboard');
})->middleware(['auth', 'verified'])->name('album-dashboard');

require __DIR__ . '/auth.php';
