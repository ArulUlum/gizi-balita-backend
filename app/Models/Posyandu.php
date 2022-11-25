<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

    protected $table = "data_posyandu";

    protected $fillable = [
        'nama',
        'alamat',
        'latitude',
        'longitude',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id');
    }

    public function anak()
    {
        return $this->hasMany(Anak::class, 'id_posyandu', 'id');
    }

    public function laporanBerat()
    {
        $obesitas = $this->anak()->where('berat_terakhir', 'Obesitas')->count();
        $gemuk = $this->anak()->where('berat_terakhir', 'Gemuk')->count();
        $normal = $this->anak()->where('berat_terakhir', 'Normal')->count();
        $kurus = $this->anak()->where('berat_terakhir', 'Kurus')->count();
        $sangatKurus = $this->anak()->where('berat_terakhir', 'Sangat Kurus')->count();

        return [
            'obesitas' =>       $obesitas,
            'gemuk' =>       $gemuk,
            'normal' =>       $normal,
            'kurus' =>       $kurus,
            'sangat_kurus' =>       $sangatKurus,
        ];
    }

    public function laporanTinggi()
    {
        $tinggi = $this->anak()->where('tinggi_terakhir', 'Tinggi')->count();
        $normal = $this->anak()->where('tinggi_terakhir', 'Normal')->count();
        $pendek = $this->anak()->where('tinggi_terakhir', 'Pendek')->count();
        $sangatPendek = $this->anak()->where('tinggi_terakhir', 'Sangat Pendek')->count();
        return [
            'tinggi' =>       $tinggi,
            'normal' =>       $normal,
            'pendek' =>       $pendek,
            'sangat_pendek' =>       $sangatPendek,
        ];
    }

    public function laporanLingkarKepala()
    {
        $makrosefali = $this->anak()->where('lingkar_kepala_terakhir', 'Makrosefali')->count();
        $normal = $this->anak()->where('lingkar_kepala_terakhir', 'Normal')->count();
        $mikrosefali = $this->anak()->where('lingkar_kepala_terakhir', 'Mikrosefali')->count();
        return [
            'makrosefali' =>       $makrosefali,
            'normal' =>       $normal,
            'mikrosefali' =>       $mikrosefali,
        ];
    }
}
