<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = false;
    protected $keyType = 'string';

    // ✅ FIX: Aktifkan timestamps Laravel
    public $timestamps = true;

    // ✅ Mapping kolom database ke Laravel timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_pengaduan',
        'nama',
        'no_hp',
        'judul_laporan',
        'isi_laporan',
        'lokasi',
        'lampiran',
        'status',
        'id_admin',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ✅ Relasi 1-to-1: Pengaduan → Berita
     * Satu pengaduan hanya bisa menjadi satu berita
     */
    public function news()
    {
        return $this->hasOne(News::class, 'id_pengaduan', 'id_pengaduan');
    }

    /**
     * ✅ Helper: Cek apakah sudah jadi berita
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * ✅ Helper: Cek apakah sudah jadi berita
     */
    public function hasNews()
    {
        return $this->news()->exists();
    }

    /**
     * ✅ Accessor: Badge status
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Pending</span>',
            'approved' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Approved</span>',
            default => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>',
        };
    }
}
