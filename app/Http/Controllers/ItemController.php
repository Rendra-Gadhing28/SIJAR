<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Menampilkan list barang dengan filter kategori
     */
     public function index(Request $request)
    {
        // Query builder untuk items
        $query = Items::query();
        
        // Inisialisasi variabel kategori
        $kategori = 'Semua Kategori';
        
        // Filter berdasarkan kategori jurusan jika ada
        if ($request->has('kategori_jurusan_id') && $request->kategori_jurusan_id != '') {
            $query->where('kategori_jurusan_id', $request->kategori_jurusan_id);
            
            // Ambil nama kategori untuk ditampilkan
            $kategoriObj = Kategori::find($request->kategori_jurusan_id);
            if ($kategoriObj) {
                $kategori = $kategoriObj->nama_kategori;
            }
        }
        
        // Filter hanya barang yang tersedia (optional)
        // $query->where('status_item', 'tersedia');
        
        // Ambil data dengan relasi kategori
        $data = $query->with('kategoriJurusan')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        
        return view('user.listbarang', compact('data', 'kategoris', 'kategori'));
    }
    
    /**
     * Menampilkan gambar yang terenkripsi
     */
    public function showImage($filename)
    {
        try {
            // Cek apakah filename sudah terenkripsi atau masih path biasa
            // Jika foto_barang berisi path file (bukan encrypted data)
            if (Storage::exists('encrypted/' . $filename)) {
                $encryptedImage = Storage::get('encrypted/' . $filename);
                $decryptedImage = Crypt::decrypt($encryptedImage);
            } 
            // Jika foto_barang berisi encrypted data langsung di database
            else {
                // Dekripsi langsung dari data
                $decryptedImage = Crypt::decrypt($filename);
            }
            
            // Deteksi MIME type
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($decryptedImage);
            
            // Return response dengan cache
            return response($decryptedImage)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=3600');
                
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Jika gagal decrypt, kembalikan placeholder
            return response()->file(public_path('images/placeholder.png'));
        } catch (\Exception $e) {
            return response()->file(public_path('images/placeholder.png'));
        }
    }
}