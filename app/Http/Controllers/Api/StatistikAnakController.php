<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StatistikResource;
use App\Models\StatistikAnak;
use App\Models\Anak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatistikAnakController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'id_anak' => ['required', 'integer', 'exists:data_anak,id'],
            'tinggi' => ['required'],
            'berat' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data anak", $validator->errors());
        }

        $anak = Anak::find($request->id_anak);

        if (empty($anak)) {
            return $this->errorNotFound("Data Anak Tidak Ditemukan");
        }

        if ($request->z_score_berat <= -3) {
            $status_berat_badan = 'Sangat Kurus';
        } else if ($request->z_score_berat > -3 && $request->z_score_berat <= -2) {
            $status_berat_badan = 'Kurus';
        } else if ($request->z_score_berat > -2 && $request->z_score_berat <= 1) {
            $status_berat_badan = 'Normal';
        } else if ($request->z_score_berat > 1 && $request->z_score_berat <= 2) {
            $status_berat_badan = 'Gemuk';
        } else if ($request->z_score_berat > 2) {
            $status_berat_badan = 'Obesitas';
        }

        if ($request->z_score_tinggi <= -3) {
            $status_tinggi_badan = 'Sangat Pendek';
        } else if ($request->z_score_tinggi > -3 && $request->z_score_tinggi <= -2) {
            $status_tinggi_badan = 'Pendek';
        } else if ($request->z_score_tinggi > -2 && $request->z_score_tinggi <= 3) {
            $status_tinggi_badan = 'Normal';
        } else if ($request->z_score_tinggi > 3) {
            $status_tinggi_badan = 'Tinggi';
        }

        if ($request->z_score_lingkar_kepala > 2) {
            $status_lingkar_kepala = 'Makrosefalus';
        } else if ($request->z_score_lingkar_kepala > -2 && $request->z_score_lingkar_kepala <= 2) {
            $status_lingkar_kepala = 'Normal';
        } else if ($request->z_score_lingkar_kepala < -2) {
            $status_lingkar_kepala = 'Microcephaly';
        }

        $statistik = StatistikAnak::create([
            'id_anak' => $request->id_anak,
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'date' => $request->date,
            'z_score_berat' => $request->z_score_berat,
            'z_score_tinggi' => $request->z_score_tinggi,
            'z_score_lingkar_kepala' => $request->z_score_lingkar_kepala,
            'status_berat_badan' => $status_berat_badan,
            'status_tinggi_badan' => $status_tinggi_badan,
            'status_lingkar_kepala' => $status_lingkar_kepala,
        ]);

        $statistik->save();

        return $this->successResponse("Success");
    }


    public function show($id)
    {
        $anak = Anak::find($id);

        if (empty($anak)) {
            return $this->errorNotFound("Data Pekembangan Anak Tidak Ditemukan");
        }

        $statistik = $anak->statistik()->get();
        $response = StatistikResource::collection($statistik);


        return $this->successResponse("list statistik anak", $response);
    }


    public function update(Request $request, $id)
    {
        $statistik = StatistikAnak::findOrFail($id);

        if (!$statistik) {
            return $this->errorNotFound("Data Perkembangan Anak Tidak Ditemukan");
        }

        if ($request->z_score_berat <= -3) {
            $status_berat_badan = 'Sangat Kurus';
        } else if ($request->z_score_berat > -3 && $request->z_score_berat <= -2) {
            $status_berat_badan = 'Kurus';
        } else if ($request->z_score_berat > -2 && $request->z_score_berat <= 1) {
            $status_berat_badan = 'Normal';
        } else if ($request->z_score_berat > 1 && $request->z_score_berat <= 2) {
            $status_berat_badan = 'Gemuk';
        } else if ($request->z_score_berat > 2) {
            $status_berat_badan = 'Obesitas';
        }

        if ($request->z_score_tinggi <= -3) {
            $status_tinggi_badan = 'Sangat Pendek';
        } else if ($request->z_score_tinggi > -3 && $request->z_score_tinggi <= -2) {
            $status_tinggi_badan = 'Pendek';
        } else if ($request->z_score_tinggi > -2 && $request->z_score_tinggi <= 3) {
            $status_tinggi_badan = 'Normal';
        } else if ($request->z_score_tinggi > 3) {
            $status_tinggi_badan = 'Tinggi';
        }

        if ($request->z_score_lingkar_kepala > 2) {
            $status_lingkar_kepala = 'Makrosefalus';
        } else if ($request->z_score_lingkar_kepala > -2 && $request->z_score_lingkar_kepala <= 2) {
            $status_lingkar_kepala = 'Normal';
        } else if ($request->z_score_lingkar_kepala < -2) {
            $status_lingkar_kepala = 'Microcephaly';
        }

        $statistik = StatistikAnak::create([
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'date' => $request->date,
            'z_score_berat' => $request->z_score_berat,
            'z_score_tinggi' => $request->z_score_tinggi,
            'z_score_lingkar_kepala' => $request->z_score_lingkar_kepala,
            'status_berat_badan' => $status_berat_badan,
            'status_tinggi_badan' => $status_tinggi_badan,
            'status_lingkar_kepala' => $status_lingkar_kepala,
        ]);

        $statistik->save();

        return $this->successResponse("Success Update Data Statistik");
    }


    public function destroy($id)
    {
        $anak = StatistikAnak::findOrFail($id);

        if (!$anak->delete()) {
            return $this->errorValidationResponse("Gagal Delete Data Perkembangan Anak");
        }

        return $this->successResponse("Success Delete Data Statistik");
    }
}
