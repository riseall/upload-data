<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

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
    return view('auth/login');
})->name('home');

Route::get('/login', function () {
    return view('auth/login');
})->name('login');

Route::get('/regUser', function () {
    return view('auth/register');
})->name('register');

Route::middleware('web')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/upload', [UploadController::class, 'showUpload'])->name('upload.show');
    Route::get('/data', [UploadController::class, 'showData'])->name('data.show'); // Nama rute yang lebih jelas

    Route::post('/upload', [UploadController::class, 'uploadData'])->name('upload.data');
});
