<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
        'deskripsi'
    ];

    // Relationship
    public function news()
    {
        return $this->hasMany(News::class, 'id_kategori', 'id_kategori');
    }
}
