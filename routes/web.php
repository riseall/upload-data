<?php

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
    return view('index');
});
Route::get('/upload', [UploadController::class, 'showUpload'])->name('upload.show');
Route::post('/upload', [UploadController::class, 'uploadData'])->name('upload.data');
Route::get('/data', [UploadController::class, 'showData'])->name('upload.show');
