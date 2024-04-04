<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function registeruser(Request $request) 
    {
    
        // Membuat class User baru dengan data request
        $generateID = Uuid::uuid4()->toString();
        $data['id'] = $generateID;
        $data['nama'] = $request->nama;
        $data['email'] = $request->email;
        $data['deskripsi'] = "-";
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

    public function test()
    {
        return response()->json(['error' => 'Registration failed'], 400);
    } 
}
