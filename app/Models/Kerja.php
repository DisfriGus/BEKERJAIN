<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerja extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_lowongan',
        'id_perusahaan',
        'id_user',
        'tgl_mulai',
        'tgl_akhir',
        'nama_posisi',
        'status',
    ];
}
