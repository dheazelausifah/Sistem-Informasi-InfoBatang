<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'karir';
    protected $primaryKey = 'id_karir';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_karir',
        'judul',
        'tipe_pekerjaan',
        'lokasi',
        'level',
        'gaji',
        'deskripsi',
        'tanggung_jawab',
        'kualifikasi',
        'status',
        'id_admin'
    ];

    // Relationship
    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_admin');
    }
}
