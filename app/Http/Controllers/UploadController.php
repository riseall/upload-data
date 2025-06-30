<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Otif;
use App\Models\SellingOut;
use App\Models\Top;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Csv\Reader;

class UploadController extends Controller
{
    public function showData()
    {
        $otifData = Otif::orderBy('id', 'desc')->limit(10)->get();
        $topData = Top::orderBy('id', 'desc')->limit(10)->get();
        $sellingOutData = SellingOut::orderBy('id', 'desc')->limit(10)->get();
        $inventoryData = Inventory::orderBy('id', 'desc')->limit(10)->get();

        return view('upload', compact('otifData', 'topData', 'sellingOutData', 'inventoryData'));
    }

    public function uploadData(Request $request)
    {
        // validasi Untuk file csv
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,text/plain|max:2048',
            'data_type' => 'required|in:otif,top,selling_out,inventory',
        ]);

        $dataType = $request->input('data_type');
        $file = request()->file('csv_file');

        try {
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setHeaderOffset(0); // Jika baris pertama adalah header

            foreach ($csv as $data) {

                if ($dataType === 'otif') {
                    // Logika untuk menyimpan data Otif
                    Otif::create([
                        'produk' => $data['produk'],
                        'jumlah_pesanan' => $data['jumlah_pesanan'],
                        'jumlah_terkirim' => $data['jumlah_terkirim'],
                        'tanggal_pesanan' => Carbon::parse($data['tanggal_pesanan']),
                        'tanggal_kirim' => Carbon::parse($data['tanggal_kirim']),
                    ]);
                } elseif ($dataType === 'top') {
                    // Logika untuk menyimpan data TOP
                    Top::create([
                        'nama_target' => $data['nama_target'],
                        'target_value' => $data['target_value'],
                        'tanggal_target' => Carbon::parse($data['tanggal_target']),
                    ]);
                } elseif ($dataType === 'selling_out') {
                    // Logika untuk menyimpan data Selling Out
                    SellingOut::create([
                        'nama_produk' => $data['nama_produk'],
                        'jumlah_terjual' => $data['jumlah_terjual'],
                        'tanggal_jual' => Carbon::parse($data['tanggal_jual']),
                    ]);
                } elseif ($dataType === 'inventory') {
                    // Logika untuk menyimpan data Inventory
                    Inventory::create([
                        'nama_barang' => $data['nama_barang'],
                        'stok' => $data['stok'],
                        'lokasi' => $data['lokasi']
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Data berhasil diunggah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah data: ' . $e->getMessage());
        }
    }
}
