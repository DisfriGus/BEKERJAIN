<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function editUserDescription(Request $request, $id)
    {
        // Pencarian user
        $user = User::find($request->id);

        Log::info("Nama before assignment: " . $user->nama);

        $data['id'] = $user->id;
        $data['nama'] = $user->nama;
        $data['email'] = $user->email;
        $data['deskripsi'] = $request->des;
        $data['password'] = ($user->password);
        $data['tanggal_lahir'] = $user->tanggal_lahir;

        $user->nama = $data['nama'];

        return response()->json([
            'nama' => $request->nama,
            'namadata' => $data['nama'],
            'user' => $user, // Include 'nama' attribute
          ], 200);
    }
}
