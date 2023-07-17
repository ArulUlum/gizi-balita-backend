<?php

namespace App\Http\Controllers\Api;

class HitungZScoreController
{
    public function HitungZScoreBerat(int $berat): array 
    {
        $z_score_berat = $berat / 20;
        if ($z_score_berat <= -3) {
            $status_berat_badan = 'Sangat Kurus';
        } else if ($z_score_berat > -3 && $z_score_berat <= -2) {
            $status_berat_badan = 'Kurus';
        } else if ($z_score_berat > -2 && $z_score_berat <= 2) {
            $status_berat_badan = 'Normal';
        } else if ($z_score_berat > 2) {
            $status_berat_badan = 'Gemuk';
        }
        return [
            'z_score_berat' => $z_score_berat,
            'status_berat_badan'=> $status_berat_badan
        ];
    }

    public function HitungZScoreTinggi(int $tinggi): array 
    {
        $z_score_tinggi = $tinggi / 20;
        if ($z_score_tinggi <= -3) {
            $status_tinggi_badan = 'Sangat Pendek';
        } else if ($z_score_tinggi > -3 && $z_score_tinggi <= -2) {
            $status_tinggi_badan = 'Pendek';
        } else if ($z_score_tinggi > -2 && $z_score_tinggi <= 3) {
            $status_tinggi_badan = 'Normal';
        } else if ($z_score_tinggi > 3) {
            $status_tinggi_badan = 'Tinggi';
        }
        return [
            'z_score_tinggi' => $z_score_tinggi,
            'status_tinggi_badan'=> $status_tinggi_badan
        ];
    }

    public function HitungZScoreLiLA(int $LiLA): array 
    {
        $z_score_lingkar_kepala = $LiLA / 20;
        if ($z_score_lingkar_kepala > 2) {
            $status_lingkar_kepala = 'Makrosefali';
        } else if ($z_score_lingkar_kepala > -2 && $z_score_lingkar_kepala <= 2) {
            $status_lingkar_kepala = 'Normal';
        } else if ($z_score_lingkar_kepala < -2) {
            $status_lingkar_kepala = 'Mikrosefali';
        }
        return [
            'z_score_lingkar_kepala' => $z_score_lingkar_kepala,
            'status_lingkar_kepala'=> $status_lingkar_kepala
        ];
    }
}