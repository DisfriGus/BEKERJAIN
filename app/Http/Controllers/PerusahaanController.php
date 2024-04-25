<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\Lowongan;
use App\Models\Kerja;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PerusahaanController extends Controller
{
    public function getAllPerusahaan()
    {
        // Mengambil semua perusahaan
        $perusahaans = Perusahaan::all();
        return response()->json($perusahaans, 200);
    }

    public function createLowongan(Request $request, $id)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'deskripsi_pekerjaan' => 'required|string',
            'kualifikasi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'slot_posisi' => 'required|integer',
            'gaji_dari' => 'required|integer',
            'gaji_hingga' => 'required|integer',
        ]);

        // Membuat lowongan baru
        $generateID = Uuid::uuid4()->toString();
        $lowongan = Lowongan::create([
            'id' => $generateID,
            'nama_posisi' => $validatedData['nama_posisi'],
            'deskripsi_pekerjaan' => $validatedData['deskripsi_pekerjaan'],
            'lokasi' => $validatedData['lokasi'],
            'kualifikasi' => $validatedData['kualifikasi'],
            'open' => true,
            'slot_posisi' => $validatedData['slot_posisi'],
            'gaji_dari' => $validatedData['gaji_dari'],
            'gaji_hingga' => $validatedData['gaji_hingga'],
            'id_perusahaan' => $id,
        ]);

        // Return response
        return response()->json($lowongan, 201);
    }

    public function editLowongan(Request $request, $id)
    {
        $lowongan = Lowongan::find($id);
        if (!$lowongan) {
            return response()->json(['error' => 'Lowongan tidak ditemukan'], 404);
        }

        // Validasi
        $validatedData = $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'deskripsi_pekerjaan' => 'required|string',
            'kualifikasi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'open' => 'required|boolean',
            'slot_posisi' => 'required|integer',
            'gaji_dari' => 'required|integer',
            'gaji_hingga' => 'required|integer',
        ]);

        // Update data lowongan
        $lowongan->nama_posisi = $validatedData['nama_posisi'];
        $lowongan->deskripsi_pekerjaan = $validatedData['deskripsi_pekerjaan'];
        $lowongan->kualifikasi = $validatedData['kualifikasi'];
        $lowongan->lokasi = $validatedData['lokasi'];
        $lowongan->open = $validatedData['open'];
        $lowongan->slot_posisi = $validatedData['slot_posisi'];
        $lowongan->gaji_dari = $validatedData['gaji_dari'];
        $lowongan->gaji_hingga = $validatedData['gaji_hingga'];

        // Update lowongan
        $lowongan->save();


        // Response berhasil
        return response()->json($lowongan, 200);
    }

    public function deleteLowongan($id)
    {
        // Mengambil lowongan dengan id == $id
        $lowongan = Lowongan::find($id);

        // Validasi
        if (!$lowongan) {
            return response()->json(['error' => 'Lowongan not found'], 404);
        }

        // Update status dan tgl_akhir kerja
        Kerja::where('id_lowongan', $id)
            ->update([
                'status' => 'terhapus',
                'tgl_akhir' => Carbon::now()
            ]);

        // Delete lowongan
        $lowongan->delete();

        // Response sukses
        return response()->json(['message' => 'Lowongan deleted successfully'], 200);
    }

    public function checkPerusahaanLowongan($id)
    {
        // Mengambil semua lowongan dengan id sesuai
        $lowongans = Lowongan::where('id_perusahaan', $id)->get();

        // Return semua lowongan dengan id terkait
        return response()->json($lowongans, 200);
    }

    public function checkPendaftarLowongan($id)
    {
        // Mengambil kerja dengan id_lowongan == $id
        $pendaftar = Kerja::where('id_lowongan', $id)
            ->where('status', 'applied')
            ->get();

        // Ambil ID user dari pendaftar
        $id_users = $pendaftar->pluck('id_user');

        // Mengambil data lengkap user yang sesuai dengan ID
        $users = User::whereIn('id', $id_users)->get();

        return response()->json($users, 200);
    }

    public function terimaPendaftarLowongan($idLowongan, $idUser)
    {
        // Pencarian kerja
        $kerja = Kerja::where('id_lowongan', $idLowongan)
            ->where('id_user', $idUser)
            ->first();

        // Validasi
        if (!$kerja) {
            return response()->json(['error' => 'Lamaran tidak ditemukan'], 404);
        }

        // Update status kerja
        $kerja->status = 'diterima';
        $kerja->tgl_mulai = Carbon::now();
        $kerja->save();

        // Response berhasil
        return response()->json(['message' => 'Pelamar diterima'], 200);
    }

    public function tolakPendaftarLowongan($idLowongan, $idUser)
    {
        // Pencarian kerja
        $kerja = Kerja::where('id_lowongan', $idLowongan)
            ->where('id_user', $idUser)
            ->first();

        // Validasi
        if (!$kerja) {
            return response()->json(['error' => 'Lamaran tidak ditemukan'], 404);
        }

        // Update status kerja
        $kerja->status = 'ditolak';
        $kerja->save();

        // Response berhasil
        return response()->json(['message' => 'Pelamar ditolak'], 200);
    }
    public function selesaikanPekerjaLowongan($idLowongan, $idUser)
    {
        // Pencarian kerja
        $kerja = Kerja::where('id_lowongan', $idLowongan)
                      ->where('id_user', $idUser)
                      ->first();
    
        // Validasi
        if (!$kerja) {
            return response()->json(['error' => 'Lamaran tidak ditemukan'], 404);
        }
    
        // Update status kerja
        $kerja->status = 'selesai';
        $kerja->tgl_akhir = Carbon::now();
        $kerja->save();
    
        // Response berhasil
        return response()->json(['message' => 'Pekerja berhenti'], 200);
    }

    public function checkPegawai($id)
    {
        $pendaftar = Kerja::where('id_lowongan', $id)
            ->where('status', 'diterima')
            ->get();

        // Ambil ID user dari pendaftar
        $id_users = $pendaftar->pluck('id_user');

        // Mengambil data lengkap user yang sesuai dengan ID
        $users = User::whereIn('id', $id_users)->get();

        return response()->json($users, 200);
    }

    public function editPerusahaanPFP($id, Request $request)
    {
        // Pencarian perusahaan
        $user = Perusahaan::find($id);

        // Validasi bahwa request memiliki file gambar yang diunggah
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Contoh validasi untuk file gambar
        ]);

        // Proses penyimpanan gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $id . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);

            // Buat URL untuk gambar yang diunggah
            $imageUrl = asset('images/' . $imageName);

            // Penyimpanan ke database
            $user->profile_picture = $imageUrl;
            $user->save();

            // Berikan respons dengan URL gambar yang diunggah
            return response()->json(['image_url' => $imageUrl], 200);
        }

        // Berikan respons sukses atau sesuaikan dengan kebutuhan aplikasi jika tidak ada gambar yang diunggah
        return response()->json(['message' => 'Upload image gagal'],200);
    }
}