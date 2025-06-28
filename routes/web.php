<?php

use App\Models\Otif;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use League\Csv\Reader;

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
    return view('upload');
});

Route::post('/', function () {
    $file = request()->file('csv_file');

    $csv = Reader::createFromPath($file->getRealPath(), 'r');
    // $csv->setDelimiter(';');
    $csv->setHeaderOffset(0); // Jika baris pertama adalah header

    $count = 0;

    foreach ($csv as $data) {
        Otif::create([
            'produk' => $data['produk'],
            'jumlah_pesanan' => $data['jumlah_pesanan'],
            'jumlah_terkirim' => $data['jumlah_terkirim'],
            'tanggal_pesanan' => Carbon::parse($data['tanggal_pesanan']),
            'tanggal_kirim' => Carbon::parse($data['tanggal_kirim']),
        ]);

        $count++;
    };
});