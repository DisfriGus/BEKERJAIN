<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function editUserDescription(Request $request, $id)
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
}
