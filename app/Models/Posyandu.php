<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function jumlahAnak()
    {
        return $this->anak()->count();
    }

    public function laporanBerat($latestStatistik)
    {
        $obesitas = 0;
        $gemuk = 0;
        $normal = 0;
        $kurus = 0;
        $sangatKurus = 0;

        if ($latestStatistik->status_berat_badan == 'Obesitas') {
            $obesitas++;
        } else if ($latestStatistik->status_berat_badan == 'Gemuk') {
            $gemuk++;
        } else if ($latestStatistik->status_berat_badan == 'Normal') {
            $normal++;
        } else if ($latestStatistik->status_berat_badan == 'Kurus') {
            $kurus++;
        } else if ($latestStatistik->status_berat_badan == 'Sangat Kurus') {
            $sangatKurus++;
        }

        return [
            'obesitas' => $obesitas,
            'gemuk' => $gemuk,
            'normal' => $normal,
            'kurus' => $kurus,
            'sangat_kurus' => $sangatKurus,
        ];
    }

    public function laporanTinggi($latestStatistik)
    {

        $tinggi = 0;
        $normal = 0;
        $pendek = 0;
        $sangatPendek = 0;

        if ($latestStatistik->status_tinggi_badan == 'Tinggi') {
            $tinggi++;
        } else if ($latestStatistik->status_tinggi_badan == 'Normal') {
            $normal++;
        } else if ($latestStatistik->status_tinggi_badan == 'Pendek') {
            $pendek++;
        } else if ($latestStatistik->status_tinggi_badan == 'Sangat Pendek') {
            $sangatPendek++;
        }

        return [
            'tinggi' => $tinggi,
            'normal' => $normal,
            'pendek' => $pendek,
            'sangat_pendek' =>  $sangatPendek,
        ];
    }

    public function laporanLingkarKepala($latestStatistik)
    {
        $makrosefali = 0;
        $normal = 0;
        $mikrosefali = 0;

        if ($latestStatistik->status_lingkar_kepala == 'Makrosefali') {
            $makrosefali++;
        } else if ($latestStatistik->status_lingkar_kepala == 'Normal') {
            $normal++;
        } else if ($latestStatistik->status_lingkar_kepala == 'Mikrosefali') {
            $mikrosefali++;
        }

        return [
            'makrosefali' => $makrosefali,
            'normal' => $normal,
            'mikrosefali' => $mikrosefali,
        ];
    }
}
