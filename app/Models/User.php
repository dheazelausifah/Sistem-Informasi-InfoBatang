<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    // Nonaktifkan timestamps karena tabel hanya punya created_at
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_user',
        'email',
        'created_at'
    ];

    // Relasi ke komentar
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_user', 'id_user');
    }
}
