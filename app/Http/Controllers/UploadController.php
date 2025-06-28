<?php

namespace App\Http\Controllers;

use App\Models\Otif;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Csv\Reader;

class UploadController extends Controller
{
    public function showData()
    {
        $otifData = Otif::orderBy('created_at', 'desc')->get();
        return view('upload', compact('otifData'));
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
                    Otif::create([
                        'produk' => $data['produk'],
                        'jumlah_pesanan' => $data['jumlah_pesanan'],
                        'jumlah_terkirim' => $data['jumlah_terkirim'],
                        'tanggal_pesanan' => Carbon::parse($data['tanggal_pesanan']),
                        'tanggal_kirim' => Carbon::parse($data['tanggal_kirim']),
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Data berhasil diunggah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah data: ' . $e->getMessage());
        }
    }
}
