<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',      // 'comment', 'complaint', 'career', 'news'
        'message',   // Pesan notifikasi
        'url',       // URL tujuan saat diklik
        'is_read',   // boolean
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk notifikasi belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk notifikasi sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope untuk filter berdasarkan tipe
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Helper method untuk membuat notifikasi baru
     */
    public static function create($attributes = [])
    {
        $attributes['is_read'] = $attributes['is_read'] ?? false;

        return static::query()->create($attributes);
    }

    /**
     * Accessor untuk icon berdasarkan type
     */
    public function getIconAttribute()
    {
        $icons = [
            'comment' => 'fa-comment',
            'complaint' => 'fa-exclamation-triangle',
            'career' => 'fa-briefcase',
            'news' => 'fa-newspaper'
        ];

        return $icons[$this->type] ?? 'fa-bell';
    }

    /**
     * Accessor untuk color berdasarkan type
     */
    public function getColorAttribute()
    {
        $colors = [
            'comment' => 'green',
            'complaint' => 'orange',
            'career' => 'purple',
            'news' => 'blue'
        ];

        return $colors[$this->type] ?? 'gray';
    }
}
