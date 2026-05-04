<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'form_timestamp',
        'nama',
        'no_identitas',
        'jenis_kelamin',
        'umur',
        'pendidikan',
        'pekerjaan',
        'kategori_instansi',
        'nama_instansi',
        'alamat',
        'telepon',
        'email',
        'form_email',
        'tanggal_kunjungan',
        'keperluan',
        'jenis_layanan',
        'jenis_data',
        'level_data',
        'source_key',
    ];

    public function assignment()
    {
        return $this->hasOne(GuestAssignment::class);
    }

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }
}
