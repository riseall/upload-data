<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    /**
     * Menampilkan halaman upload.
     *
     * @return \Illuminate\View\View
     */
    public function showUpload()
    {
        return view('upload');
    }

    /**
     * Memproses upload file CSV dan mengirimkannya ke API backend dengan static bearer token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadData(Request $request)
    {
        // Validasi untuk file CSV
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'data_type' => 'required|in:Master Product,Master Customer,Stock METD,Sellout Faktur,Sellout Nonfaktur',
            'api_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $dataType = $request->input('data_type');
        $file = $request->file('csv_file');
        $apiToken = $request->input('api_token');

        try {
            // Ambil URL API dan Token dari file konfigurasi
            $erpApiUrl = config('services.erp.url') . '/api/receive-data';
            // $erpApiToken = config('services.erp.token');

            // Opsi Guzzle untuk request HTTP
            $requestOptions = [];
            // Hanya nonaktifkan verifikasi SSL untuk lingkungan lokal
            if (env('APP_ENV') === 'local') {
                $requestOptions['verify'] = false;
            }

            // Inisialisasi builder HTTP request
            $requestBuilder = Http::timeout(120) // Tingkatkan timeout untuk upload file
                ->withOptions($requestOptions)
                ->acceptJson();

            // Tambahkan token bearer yang dinamis
            if ($apiToken) { // Menggunakan $apiToken yang diambil dari request
                $requestBuilder->withToken($apiToken);
            } else {
                // Jika token tidak ada (seharusnya sudah divalidasi), log error dan redirect
                Log::error('API Token tidak ditemukan di request saat upload data.');
                return redirect()->back()->with('error', 'Token otentikasi tidak ditemukan. Silakan login kembali.');
            }

            // Kirim data ke API backend, attach file secara langsung
            $response = $requestBuilder->attach(
                'csv_file', // Nama field di form
                file_get_contents($file->getRealPath()), // Konten file
                $file->getClientOriginalName(), // Nama file asli
                ['Content-Type' => $file->getClientMimeType()] // Mime type
            )->post($erpApiUrl, [
                'data_type' => $dataType,
            ]);

            // Tangani response dari API backend
            if ($response->successful()) {
                $erpMessage = $response->json('message', 'Data berhasil dikirim ke Server Kacaerp.');
                return redirect()->back()->with('success', $erpMessage);
            } else {
                $statusCode = $response->status();
                $errorMessageFromErp = $response->json('message');
                $errorDetailsFromErp = $response->json('errors') ?? $response->json('error_detail');

                $logMessage = "ERP API Error (Status: $statusCode): " . $response->body();
                Log::error($logMessage);

                $userMessage = '';
                if ($errorMessageFromErp) {
                    $userMessage .= $errorMessageFromErp;
                }
                if ($errorDetailsFromErp) {
                    $userMessage .= ' Detail: ' . (is_array($errorDetailsFromErp) ? json_encode($errorDetailsFromErp) : $errorDetailsFromErp);
                }

                return redirect()->back()->with('error', $userMessage);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Koneksi ke ERP API gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Tidak dapat terhubung ke server ERP. Pastikan URL dan koneksi sudah benar.');
        } catch (\Exception $e) {
            Log::error('Kesalahan umum saat memproses file atau mengirim data: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'Terjadi kesalahan internal saat memproses file atau mengirim data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Menampilkan data yang sudah diupload (jika ada).
     * Ini juga akan mengambil data dari API backend.
     *
     * @return \Illuminate\View\View
     */
    public function showData()
    {
        return view('data');
    }
}
