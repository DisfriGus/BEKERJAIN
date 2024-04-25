<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Kerja;
use App\Models\Lowongan;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function editUser(Request $request, $id)
    {
        // Pencarian user
        $user = User::find($id);

        // Pengecekan email dalam database
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            return ["result" => "Email sudah digunakan"];
        }

        // Update informasi user
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->deskripsi = $request->deskripsi;
        $result = $user->save();

        if ($result) {
            return ["result" => "Data is updated"];
        } else {
            return ["result" => "Data isn't updated"];
        }
    }

    public function checkUserExperience($id)
    {
        // Mencari kerjaan selesai dengan id_user yang sesuai
        $items = Kerja::where('id_user', $id)
            ->where('status', 'finished')
            ->get();

        // Return the retrieved items as a JSON response
        return response()->json($items, 200);
    }

    public function applyLowongan(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'id_lowongan' => 'required',
            'id_user' => 'required',
        ]);

        // Pengambilan data dengan id yang sesuai
        $lowongan = Lowongan::find($validatedData['id_lowongan']);

        // Validasi apakah lowongan valid
        if (!$lowongan) {
            return response()->json(['error' => 'Lowongan not found'], 404);
        }

        // Validasi apakah lowongan open
        if (!$lowongan->open) {
            return response()->json(['error' => 'Lowongan is not open for application'], 400);
        }

        // Create class Kerja baru dengan ID manual
        $kerja = new Kerja();
        $kerja->id = Uuid::uuid4()->toString(); // UUID baru untuk setiap entri
        $kerja->id_lowongan = $validatedData['id_lowongan'];
        $kerja->id_perusahaan = $lowongan->id_perusahaan;
        $kerja->id_user = $validatedData['id_user'];
        $kerja->tgl_mulai = null;
        $kerja->tgl_akhir = null;
        $kerja->nama_posisi = $lowongan->nama_posisi;
        $kerja->status = 'applied';
        $kerja->save();

        // Response berhasil
        return response()->json($kerja, 201);
    }

    public function getAllPerusahaan()
    {
        $perusahaans = Perusahaan::all();
        return response()->json($perusahaans, 200);
    }

    public function getAllLowongan()
    {
        $lowongans = Lowongan::all();
        return response()->json($lowongans, 200);
    }

    public function getLowonganInfo($id)
    {
        $lowongan = Lowongan::find($id);

        // Validasi
        if (!$lowongan) {
            return response()->json(['error' => 'Lowongan tidak ditemukan'], 404);
        }
        return response()->json($lowongan, 200);
    }
    public function checkUserLowonganStatus($id)
    {
        // Mencari kerjaan selesai dengan id_user yang sesuai
        $items = Kerja::where('id_user', $id)
                  ->get();

        // Return the retrieved items as a JSON response
        return response()->json($items, 200);
    }

    public function getAllKerja()
    {
        $kerjas = Kerja::all();

        return response()->json($kerjas, 200);
    }
}
