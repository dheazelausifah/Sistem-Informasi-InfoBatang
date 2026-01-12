<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'berita';
    protected $primaryKey = 'id_berita';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_berita',
        'judul',
        'isi',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'gambar',
        'tanggal_publish',
        'status',
        'id_kategori',
        'id_admin'
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
        'tanggal_kejadian' => 'date',
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'draft') {
            return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>';
        } elseif ($this->status == 'publish') {
            return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>';
        }
        return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>';
    }

    // Accessor untuk image URL
    public function getImageUrlAttribute()
    {
        if ($this->gambar && file_exists(public_path($this->gambar))) {
            return asset($this->gambar);
        }
        return asset('images/news/berita.png'); // Default image
    }
}
