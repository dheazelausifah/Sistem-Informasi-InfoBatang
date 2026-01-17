<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_komentar',
        'id_berita',
        'id_user',
        'isi_komentar',
        'tanggal_komentar'
    ];

    protected $casts = [
        'tanggal_komentar' => 'datetime'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'id_berita', 'id_berita');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // HAPUS semua event booted()
}
