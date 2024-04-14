<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perusahaan;
use App\Models\Lowongan;
use App\Models\Kerja;

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
}
