<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatistikAnak extends Model
{
    use HasFactory;

    protected $table = 'data_statistik_anak';

    protected $fillable = [
        'tinggi',
        'berat',
        'lingkar_kepala',
        'date',
        'z_score_tinggi',
        'z_score_berat',
        'z_score_lingkar_kepala',
        'date',
        'id_anak',
        "status_berat_badan",
        "status_tinggi_badan",
        "status_lingkar_kepala"
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id');
    }

    public function kategoriBerat()
    {
        $zscore = $this->z_score_berat;
        if ($zscore > 2) {
            $berat = 'Obesitas';
        } elseif ($zscore > 1 && $zscore <= 2) {
            $berat = 'Gemuk';
        } elseif ($zscore > -2 && $zscore <= 1) {
            $berat = 'Normal';
        } elseif ($zscore > -3 && $zscore <= -2) {
            $berat = 'Kurus';
        } elseif ($zscore < -3) {
            $berat = 'Sangat Kurus';
        }

        if (empty($this->z_score_berat)) {
            return null;
        }

        return $berat;
    }

    public function kategoriTinggi()
    {
        $zscore = $this->z_score_tinggi;
        if ($zscore > 2) {
            $tinggi = 'Tinggi';
        } elseif ($zscore > -2 && $zscore <= 2) {
            $tinggi = 'Normal';
        } elseif ($zscore > -3 && $zscore <= -2) {
            $tinggi = 'Pendek';
        } elseif ($zscore < -3) {
            $tinggi = 'Sangat Pendek';
        }

        if (empty($this->z_score_tinggi)) {
            return null;
        }

        return $tinggi;
    }

    public function kategoriLingkarKepala()
    {
        $zscore = $this->z_score_lingkar_kepala;
        if ($zscore > 2) {
            $lingkarKepala = 'Makrosefali';
        } elseif ($zscore > -2 && $zscore <= 2) {
            $lingkarKepala = 'Normal';
        } elseif ($zscore < -2) {
            $lingkarKepala = 'Mikrosefali';
        }

        if (empty($this->z_score_lingkar_kepala)) {
            return null;
        }

        return $lingkarKepala;
    }
}
