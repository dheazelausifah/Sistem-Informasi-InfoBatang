<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    protected $keyType = 'string';

    // karena tabel admin punya created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'id_admin',
        'nama_admin',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Auto hash password ketika di-set
     */
    public function setPasswordAttribute($value)
    {
        // Hanya hash jika password belum di-hash
        if (!empty($value)) {
            // Cek apakah sudah di-hash dengan bcrypt (dimulai dengan $2y$)
            if (!str_starts_with($value, '$2y$')) {
                $this->attributes['password'] = Hash::make($value);
            } else {
                $this->attributes['password'] = $value;
            }
        }
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'id_admin', 'id_admin');
    }
}
