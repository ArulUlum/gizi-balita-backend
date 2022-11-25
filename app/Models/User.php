<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'alamat',
        'password',
        'id_desa',
        'id_posyandu',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role() {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    public function desa() {
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }

    public function posyandu() {
        return $this->belongsTo(Posyandu::class, 'id_posyandu', 'id');
    }

    public function anak() {
        return $this->hasMany(Anak::class, 'id_orang_tua', 'id');
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     * @return User
     */
    public static function getUser($user) : User {
        return $user;
    }
}
