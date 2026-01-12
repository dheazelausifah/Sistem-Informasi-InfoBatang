<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengaduan',
        'nama',
        'no_hp',
        'judul_laporan',
        'isi_laporan',
        'lokasi',
        'lampiran',
        'status',
        'id_admin'
    ];
}
