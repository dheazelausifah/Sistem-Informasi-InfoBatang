<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_admin',
        'nama_admin',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationships
    public function news()
    {
        return $this->hasMany(News::class, 'id_admin', 'id_admin');
    }

    public function careers()
    {
        return $this->hasMany(Career::class, 'id_admin', 'id_admin');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'id_admin', 'id_admin');
    }
}
