<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anak;

class StatistikAnakController extends Controller
{
    public function show($id)
    {
        $anak = Anak::find($id);
        if (empty($anak)) {
            redirect('/dashboard');
        }

        $response = [
            'anak' => $anak,
        ];

        return view('input-statistik', $response);
    }

    public function store(Request $request, $id)
    {
        $anak = Anak::find($id);
        if (empty($anak)) {
            redirect('/dashboard');
        }

        $anak->statistik()->create([
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'z_score_tinggi' => $request->z_score_tinggi,
            'z_score_berat' => $request->z_score_berat,
            'z_score_lingkar_kepala' => $request->z_score_lingkar_kepala,
            'date' => now(),
        ]);

        $anak->push();

        return redirect("/detail-anak/" . $anak->id);
    }
}
