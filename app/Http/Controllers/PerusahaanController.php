<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\Lowongan;
use App\Models\Kerja;

use Carbon\Carbon;

class PerusahaanController extends Controller
{
    public function getAllPerusahaan()
    {
        $perusahaans = Perusahaan::all();
        return response()->json($perusahaans, 200);
    }

    public function createLowongan(Request $request, $id)
    {
        // Validasi data
        $validatedData = $request->validate([
            'nama_posisi' => 'required|string|max:255',
            'deskripsi_pekerjaan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'open' => 'required|boolean',
            'slot_posisi' => 'required|integer',
            'gaji_bulanan' => 'required|integer',
        ]);

        // Membuat lowongan baru
        $lowongan = Lowongan::create([
            'nama_posisi' => $validatedData['nama_posisi'],
            'deskripsi_pekerjaan' => $validatedData['deskripsi_pekerjaan'],
            'lokasi' => $validatedData['lokasi'],
            'open' => $validatedData['open'],
            'slot_posisi' => $validatedData['slot_posisi'],
            'gaji_bulanan' => $validatedData['gaji_bulanan'],
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
            'lokasi' => 'required|string|max:255',
            'open' => 'required|boolean',
            'slot_posisi' => 'required|integer',
            'gaji_bulanan' => 'required|integer',
        ]);

        // Update data lowongan
        $lowongan->nama_posisi = $validatedData['nama_posisi'];
        $lowongan->deskripsi_pekerjaan = $validatedData['deskripsi_pekerjaan'];
        $lowongan->lokasi = $validatedData['lokasi'];
        $lowongan->open = $validatedData['open'];
        $lowongan->slot_posisi = $validatedData['slot_posisi'];
        $lowongan->gaji_bulanan = $validatedData['gaji_bulanan'];
        
        // Update lowongan
        $lowongan->save();

        // Response berhasil
        return response()->json($lowongan, 200);
    }

    public function deleteLowongan($id)
    {
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
        // Mendapatkan class kerja dengan id sesuai dan status == "applied"
        $pendaftar = Kerja::where('id_lowongan', $id)
                            ->where('status', 'applied')
                            ->get();

        // Response berhasil
        return response()->json($pendaftar, 200);
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
}
