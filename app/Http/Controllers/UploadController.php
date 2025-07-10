<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Otif;
use App\Models\SellingOut;
use App\Models\Top;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showData()
    {
        $otifData = Otif::orderBy('id', 'desc')->get();
        $topData = Top::orderBy('id', 'desc')->limit(10)->get();
        $sellingOutData = SellingOut::orderBy('id', 'desc')->limit(10)->get();
        $inventoryData = Inventory::orderBy('id', 'desc')->limit(10)->get();

        return view('data', compact('otifData', 'topData', 'sellingOutData', 'inventoryData'));
    }

    public function showUpload()
    {
        return view('upload');
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
        $uploadDate = Carbon::now()->toDateString(); //tgl upload hari ini

        try {
            // Hapus data lama untuk tipe data yang sama pada hari ini
            $this->clearExistingData($dataType, $uploadDate);

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
            // Tangkap dan log error secara lebih detail di produksi
            Log::error('Gagal mengunggah data CSV: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Gagal mengunggah data: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data yang sudah ada untuk tipe dan tanggal upload yang sama.
     *
     * @param string $dataType
     * @param string $uploadDate (YYYY-MM-DD)
     * @return void
     */
    protected function clearExistingData(string $dataType, string $uploadDate): void
    {
        switch ($dataType) {
            case 'otif':
                // Hapus data Otif yang dibuat pada tanggal upload ini
                Otif::whereDate('created_at', $uploadDate)->delete();
                break;
            case 'top':
                Top::whereDate('created_at', $uploadDate)->delete();
                break;
            case 'selling_out':
                SellingOut::whereDate('created_at', $uploadDate)->delete();
                break;
            case 'inventory':
                Inventory::whereDate('created_at', $uploadDate)->delete();
                break;
            default:
                // Handle kasus jika dataType tidak dikenali (opsional)
                break;
        }
    }
}
