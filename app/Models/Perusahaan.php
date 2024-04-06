<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Perusahaan extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nama',
        'email',
        'password',
        'deskripsi',
        'tipe',
        'tahun_berdiri',
    ];

    protected $keyType = 'string';
}
