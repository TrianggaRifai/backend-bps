<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublikasiController extends Controller
{
    /**
     * Menampilkan semua data publikasi.
     */
    public function index()
    {
        // FIX: Selalu kembalikan dalam format JSON untuk konsistensi.
        return response()->json(Publikasi::latest()->get());
    }

    /**
     * Menyimpan data publikasi baru.
     */
 public function store(Request $request)
{
    // Validasi hanya untuk cover_url
    $validated = $request->validate([
        'title'        => 'required|string|max:255',
        'release_date' => 'required|date',
        'description'  => 'nullable|string',
        'cover_url'    => 'required|string', // karena gambar sudah diupload ke Cloudinary
    ]);

    $publikasi = Publikasi::create($validated);

    return response()->json($publikasi, 201);
}


    /**
     * Menampilkan satu data publikasi.
     */
    public function show($id)
    {
        // Penggunaan findOrFail sudah sangat baik, tidak perlu diubah.
        return response()->json(Publikasi::findOrFail($id));
    }

    /**
     * Memperbarui data publikasi yang ada.
     */
    public function update(Request $request, $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        // FIX: Validasi dibuat lebih fleksibel untuk update.
        // 'sometimes' berarti validasi hanya jika field tersebut dikirim.
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'release_date' => 'sometimes|required|date',
            'description' => 'sometimes|nullable|string',
            'cover' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar tidak wajib diubah
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $validatedData = $validator->validated();

        // FIX: Logika untuk menangani update file gambar.
        if ($request->hasFile('cover')) {
            // Hapus gambar lama jika ada
            if ($publikasi->cover_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $publikasi->cover_url));
            }
            // Simpan gambar baru dan update path
            $coverPath = $request->file('cover')->store('covers', 'public');
            $validatedData['cover_url'] = Storage::url($coverPath);
        }

        $publikasi->update($validatedData);

        return response()->json($publikasi);
    }

    /**
     * Menghapus data publikasi.
     */
    public function destroy($id)
    {
        $publikasi = Publikasi::findOrFail($id);

        // Hapus file gambar dari storage saat data dihapus
        if ($publikasi->cover_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $publikasi->cover_url));
        }
        
        $publikasi->delete();

        return response()->json(['message' => 'Publikasi berhasil dihapus.']);
    }
}