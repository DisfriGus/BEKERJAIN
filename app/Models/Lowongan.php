<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nama_posisi',
        'deskripsi_pekerjaan',
        'kualifikasi',
        'lokasi',
        'open',
        'slot_posisi',
        'gaji_dari',
        'gaji_hingga',
        'id_perusahaan',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan');
    }

    protected $keyType = 'string';
}
