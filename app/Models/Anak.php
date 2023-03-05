<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anak extends Model
{
    use HasFactory;

    protected $table = 'data_anak';

    protected $fillable = [
        'nama',
        'panggilan',
        'tanggal_lahir',
        'alamat',
        'nama_orang_tua',
        'gender',
        'image',
        'berat_terakhir',
        'tinggi_terakhir',
        'lingkar_kepala_terakhir',
        'id_orang_tua',
        'id_posyandu',
        'id_desa',
    ];

    public function umur($date)
    {
        $tanggalLahir = Carbon::createFromFormat("Y-m-d", $this->tanggal_lahir);
        $carbonDate = Carbon::make($date);
        $umurInBulan = $carbonDate->diffInMonths($tanggalLahir);
        return $umurInBulan;
    }

    public function updateStatistikTerakhir()
    {
        $statistik = $this->statistik()->orderBy('created_at', 'desc')->first();
        $this->berat_terakhir = $statistik->kategoriBerat();
        $this->tinggi_terakhir = $statistik->kategoriTinggi();
        $this->lingkar_kepala_terakhir = $statistik->kategoriLingkarKepala();
        $this->update();
    }


    public function statistikTerakhir()
    {
        $statistik = $this->statistik()->orderBy('created_at', 'desc')->first();
        return $statistik;
    }

    public function beratTerakhir()
    {
        $statistik = $this->statistik()->orderBy('created_at', 'desc')->first();
        if (empty($statistik)) {
            return null;
        }
        return $statistik->berat;
    }

    public function tinggiTerakhir()
    {
        $statistik = $this->statistik()->orderBy('created_at', 'desc')->first();
        if (empty($statistik)) {
            return null;
        }
        return $statistik->tinggi;
    }
    public function lingkarKepalaTerakhir()
    {
        $statistik = $this->statistik()->orderBy('created_at', 'desc')->first();
        if (empty($statistik)) {
            return null;
        }
        return $statistik->lingkar_kepala;
    }

    public function statistik()
    {
        return $this->hasMany(StatistikAnak::class, 'id_anak', 'id');
    }

    public function orangTua()
    {
        return $this->belongsTo(User::class, 'id_orang_tua', 'id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class, 'id_posyandu', 'id');
    }
}
