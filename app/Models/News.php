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
        'lokasi',
        'gambar',
        'tanggal_publish',
        'status',
        'views',
        'id_kategori',
        'id_admin',
        'id_pengaduan'
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
        'tanggal_kejadian' => 'date',
        'views' => 'integer'
    ];

    // Relationship ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori', 'id_kategori');
    }

    // Relationship ke Comments
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_berita', 'id_berita');
    }

    // Relationship ke Complaint
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'id_pengaduan', 'id_pengaduan');
    }

    // ✅ Accessor untuk multiple images
    public function getImagesAttribute()
    {
        if (!$this->gambar) {
            return [asset('images/news/berita.png')];
        }

        $images = json_decode($this->gambar, true);

        if (!is_array($images)) {
            // Jika bukan array (gambar lama/single), return as array
            return [asset($this->gambar)];
        }

        return array_map(function($image) {
            return asset($image);
        }, $images);
    }

    // ✅ Accessor untuk first image (untuk thumbnail)
    public function getImageUrlAttribute()
    {
        $images = $this->images;
        return $images[0] ?? asset('images/news/berita.png');
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

    // Helper untuk format views
    public function getFormattedViewsAttribute()
    {
        if ($this->views >= 1000000) {
            return round($this->views / 1000000, 1) . 'M';
        } elseif ($this->views >= 1000) {
            return round($this->views / 1000, 1) . 'K';
        }
        return $this->views;
    }
}
