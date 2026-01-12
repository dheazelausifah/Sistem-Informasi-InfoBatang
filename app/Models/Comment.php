<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'komentar';

    protected $fillable = [
        'news_id',
        'user_id',
        'nama',
        'email',
        'komentar'
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function booted()
    {
        static::created(function ($comment) {
            $newsTitle = $comment->news ? $comment->news->judul : 'berita tidak ditemukan';

            \App\Models\Notification::create([
                'type' => 'comment',
                'message' => 'Komentar baru dari ' . $comment->nama . ' pada berita: ' . $newsTitle,
                'url' => route('admin.comments.index'),
                'is_read' => false
            ]);
        });
    }
}
