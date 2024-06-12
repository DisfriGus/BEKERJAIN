<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perusahaan;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerUser(Request $request) 
    {
    
        // Membuat class User baru dengan data request
        $generateID = Uuid::uuid4()->toString();
        $data['id'] = $generateID;
        $data['nama'] = $request->nama;
        $data['email'] = $request->email;
        $data['deskripsi'] = null;
        $data['password'] = bcrypt($request->password);
        $data['tanggal_lahir'] = $request->tanggal_lahir;
        
    
        // Pembuatan User baru dan update data table 
        $user = User::create($data);
    
        // Pengecekan
        if ($user) {
            // Response Sukses
            return response()->json($data, 201);
        }
        // Redirect kepada home page
        return response()->json(['error' => 'Registration failed'], 400);
    }

    public function loginUser(Request $request)
    {
        // Validate the incoming request data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Pencarian user
        if (Auth::attempt($credentials)) {
            // Pengambian user
            $user = Auth::user();
            
            // Response data user
            return response()->json($user, 200);
        } else {
            // Response gagal
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function getUser($id)
    {
        // Mencari user menggunakan ID
        $user = User::find($id);

        // Validasi
        if ($user) {
            // Response sukses
            return response()->json($user, 200);
        } else {
            // Response gagal
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function deleteUser($id)
    {
        // Mencari user menggunakan ID
        $user = User::find($id);

        // Validasi
        if ($user) {
            // Delete user
            $user->delete();
            
            // Response sukses
            return response()->json(['message' => 'User deleted successfully'], 200);
        } else {
            // Response gagal
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function registerPerusahaan(Request $request) 
    {
        // Data perusahaan
        $generateID = Uuid::uuid4()->toString();
        $data['id'] = $generateID;
        $data['nama'] = $request->nama;
        $data['email'] = $request->email;
        $data['deskripsi'] = "-";
        $data['password'] = bcrypt($request->password);
        $data['tipe'] = $request->tipe;
        $data['tahun_berdiri'] = $request->tahun_berdiri;
        
        // Pembuatan Perusahaan baru dan update data table 
        $perusahaan = Perusahaan::create($data);
        
        // Validasi
        if ($perusahaan) {
            // Response sukses
            return response()->json($data, 201);
        }
        
        // Response gagal
        return response()->json(['error' => 'Registrasi gagal'], 400);
    }

    public function loginPerusahaan(Request $request)
    {
        // Validasi
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('perusahaan')->attempt($credentials)) {
            $perusahaan = Auth::guard('perusahaan')->user();
            
            // Response berhasil
            return response()->json($perusahaan, 200);
        } else {
            // Response gagal
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function getPerusahaan($id)
    {
        // Mengambil data perusahaan dengan id == $id
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['error' => 'Perusahaan tidak ditemukan'], 404);
        }
        return response()->json($perusahaan, 200);
    }
}