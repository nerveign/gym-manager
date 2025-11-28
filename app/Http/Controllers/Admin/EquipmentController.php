<?php

namespace App\Http\Controllers\Admin;

use Cloudinary\Cloudinary;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment-detail', compact('equipment', 'user'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('admin.equipment-create', compact('user'));
    }

    // Menyimpan Data Baru
    public function store(Request $request)
    {
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'brand'          => 'required|string|max:255',
            'condition'      => 'required|string',
            'quantity'       => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            // KONFIGURASI MANUAL (Lebih Stabil)
            // Kita buat instance Cloudinary langsung menggunakan data dari .env
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            // Upload file menggunakan instance tersebut
            $uploadedFile = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'gym-equipments'] // Folder di Cloudinary
            );

            // Ambil URL hasil upload
            $imageUrl = $uploadedFile['secure_url'];
        }

        Equipment::create([
            'equipment_name' => $request->equipment_name,
            'brand'          => $request->brand,
            'condition'      => $request->condition,
            'quantity'       => $request->quantity,
            'description'    => $request->description,
            'image_url'      => $imageUrl,
        ]);

        return redirect()->route('admin.equipments_management')->with('success', 'Equipment berhasil ditambahkan!');
    }

    // === METHOD EDIT ===
    public function edit($id)
    {
        $user = Auth::user();
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment-edit', compact('equipment', 'user'));
    }

    // === METHOD UPDATE DENGAN UPLOAD GAMBAR BARU ===
    public function update(Request $request, $id)
    {
        // 1. Ambil data equipment yang sedang diedit
        $equipment = Equipment::findOrFail($id);

        // 2. Validasi input
        $request->validate([
            'equipment_name' => 'required|string|max:255',
            'brand'          => 'required|string|max:255',
            'condition'      => 'required|string',
            'quantity'       => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        // 3. Default URL gambar adalah URL yang lama (agar gambar tidak hilang jika tidak diganti)
        $imageUrl = $equipment->image_url;

        // 4. Cek apakah user mengupload gambar BARU
        if ($request->hasFile('image')) {

            // Konfigurasi Cloudinary Manual (Sama persis seperti di method store)
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            // Upload gambar baru ke Cloudinary
            $uploadedFile = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'gym-equipments']
            );

            // Ambil URL baru dari hasil upload
            $imageUrl = $uploadedFile['secure_url'];
        }

        // 5. Update data ke database
        $equipment->update([
            'equipment_name' => $request->equipment_name,
            'brand'          => $request->brand,
            'condition'      => $request->condition,
            'quantity'       => $request->quantity,
            'description'    => $request->description,
            'image_url'      => $imageUrl, // Gunakan URL baru (jika ada upload) atau tetap URL lama
        ]);

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()->route('admin.equipments.show', $id)->with('success', 'Data equipment berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);

        // Hapus data
        $equipment->delete();

        // Redirect ke halaman list dengan pesan sukses
        return redirect()->route('admin.equipments_management')->with('success', 'Equipment berhasil dihapus!');
    }
}
