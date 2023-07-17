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
        } else if ($request->z_score_berat > -2 && $request->z_score_berat <= 2) {
            $status_berat_badan = 'Normal';
        } else if ($request->z_score_berat > 2) {
            $status_berat_badan = 'Gemuk';
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
            $status_lingkar_kepala = 'Makrosefali';
        } else if ($request->z_score_lingkar_kepala > -2 && $request->z_score_lingkar_kepala <= 2) {
            $status_lingkar_kepala = 'Normal';
        } else if ($request->z_score_lingkar_kepala < -2) {
            $status_lingkar_kepala = 'Mikrosefali';
        }

        if ($request->z_score_gizi > 3) {
            $gizi = 'Obesitas';
        } elseif ($request->z_score_gizi > 2 && $request->z_score_gizi <= 3) {
            $gizi = 'Gizi Lebih';
        } elseif ($request->z_score_gizi > 1 && $request->z_score_gizi <= 2) {
            $gizi = 'Beresiko Gizi Lebih';
        } elseif ($request->z_score_gizi >= -2 && $request->z_score_gizi <= 1) {
            $gizi = 'Gizi Baik';
        } elseif ($request->z_score_gizi >= -3 && $request->z_score_gizi < -2) {
            $gizi = 'Gizi Kurang';
        } elseif ($request->z_score_gizi < -3) {
            $gizi = 'Gizi Buruk';
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
            'z_score_gizi' => $request->z_score_gizi,
            'status_gizi' => $gizi,
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

        $statistik = $anak->statistik()->orderBy('date', 'desc')->get();
        $response = StatistikResource::collection($statistik);


        return $this->successResponse("list statistik anak", $response);
    }

    public function ShowAllByOrtu()
    {
        $user = Auth::user();
        $anakId = Anak::where('id_orang_tua', $user->id)->pluck('id')->all();
        $badan = [];
        $tinggi = [];
        $lingkaran = [];
        $gizi = [];
        $response = [];
        $statisBeratBadan = 'Ideal';
        $statisTinggiBadan = 'Ideal';
        $statisLingkaranKepala = 'Ideal';
        $statisGizi = 'Ideal';

        foreach ($anakId as $id){
            $anak = Anak::find($id);
            if (empty($anak)) {
                return $this->errorNotFound("Data Pekembangan Anak Tidak Ditemukan");
            }
            $statistik = $anak->statistik()->get();
            $allStatik = StatistikResource::collection($statistik);
            
            $statikSebelum = null;
            foreach($allStatik as $rawStatik){
                $json = json_encode($rawStatik); //ubah ke json
                $statik = json_decode($json); //ubah ke php
                
                if ($statik->statistik->berat != 'Normal'){
                    $statisBeratBadan = 'Tidak Ideal';
                }
                if ($statik->statistik->tinggi != 'Normal'){
                    $statisTinggiBadan = 'Tidak Ideal';
                }
                if ($statik->statistik->lingkar_kepala != 'Normal'){
                    $statisLingkaranKepala = 'Tidak Ideal';
                }
                if ($statik->statistik->gizi != 'Gizi Baik'){
                    $statisGizi = 'Tidak Ideal';
                }

                $statisTingkatBeratBadan = 'Tetap';
                $statisTingkatTinggiBadan = 'Tetap';
                $statisTingkatLingkaranKepala = 'Tetap';
                $statisTingkatGizi = 'Tetap';
                if ($statikSebelum !== null){
                    if ($statikSebelum->statistik->berat != 'Normal' && $statik->statistik->berat == 'Normal'){
                        $statisTingkatBeratBadan = 'Meningkat';
                        
                    }
                    if ($statikSebelum->statistik->berat == 'Normal' && $statik->statistik->berat != 'Normal'){
                        $statisTingkatBeratBadan = 'Menurun';
                        
                    }
                    if ($statikSebelum->statistik->tinggi != 'Normal' && $statik->statistik->tinggi == 'Normal'){
                        $statisTingkatTinggiBadan = 'Meningkat';
                        
                    }
                    if ($statikSebelum->statistik->tinggi == 'Normal' && $statik->statistik->tinggi != 'Normal'){
                        $statisTingkatTinggiBadan = 'Menurun';
                        
                    }
                    if ($statikSebelum->statistik->lingkar_kepala != 'Normal' && $statik->statistik->lingkar_kepala == 'Normal'){
                        $statisTingkatLingkaranKepala = 'Meningkat';
                        
                    }
                    if ($statikSebelum->statistik->lingkar_kepala == 'Normal' && $statik->statistik->lingkar_kepala != 'Normal'){
                        $statisTingkatLingkaranKepala = 'Menurun';
                        
                    }
                    if ($statikSebelum->statistik->gizi != 'Gizi Baik' && $statik->statistik->gizi == 'Gizi Baik'){
                        $statisTingkatGizi = 'Meningkat';
                        
                    }
                    if ($statikSebelum->statistik->gizi == 'Gizi Baik' && $statik->statistik->gizi != 'Gizi Baik'){
                        $statisTingkatGizi = 'Menurun';
                        
                    }
                }
                $statikSebelum = $statik;
            }

            $badan['nama_status'] = 'Statistik berat badan';
            $badan['kondisi'] = $statisBeratBadan;
            $badan['perkembangan'] = $statisTingkatBeratBadan;

            $tinggi['nama_status'] = 'Statistik tinggi badan';
            $tinggi['kondisi'] = $statisTinggiBadan;
            $tinggi['perkembangan'] = $statisTingkatTinggiBadan;

            $lingkaran['nama_status'] = 'Statistik lingkaran kepala';
            $lingkaran['kondisi'] = $statisLingkaranKepala;
            $lingkaran['perkembangan'] = $statisTingkatLingkaranKepala;
            
            $gizi['nama_status'] = 'Statistik gizi';
            $gizi['kondisi'] = $statisGizi;
            $gizi['perkembangan'] = $statisTingkatGizi;
        }
        $response[] = $badan;
        $response[] = $tinggi;
        $response[] = $lingkaran;
        $response[] = $gizi;
        return $this->successResponse("list statistik anak", $response);
    }


    public function update(Request $request, $id)
    {
        $statistik = StatistikAnak::find($id);
        if (!$statistik) {
            return $this->errorNotFound("Data Perkembangan Anak Tidak Ditemukan");
        }

        if ($request->z_score_berat <= -3) {
            $status_berat_badan = 'Sangat Kurus';
        } else if ($request->z_score_berat > -3 && $request->z_score_berat <= -2) {
            $status_berat_badan = 'Kurus';
        } else if ($request->z_score_berat > -2 && $request->z_score_berat <= 2) {
            $status_berat_badan = 'Normal';
        } else if ($request->z_score_berat > 2) {
            $status_berat_badan = 'Gemuk';
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
            $status_lingkar_kepala = 'Makrosefali';
        } else if ($request->z_score_lingkar_kepala > -2 && $request->z_score_lingkar_kepala <= 2) {
            $status_lingkar_kepala = 'Normal';
        } else if ($request->z_score_lingkar_kepala < -2) {
            $status_lingkar_kepala = 'Mikrosefali';
        }

        if ($request->z_score_gizi > 3) {
            $gizi = 'Obesitas';
        } elseif ($request->z_score_gizi > 2 && $request->z_score_gizi <= 3) {
            $gizi = 'Gizi Lebih';
        } elseif ($request->z_score_gizi > 1 && $request->z_score_gizi <= 2) {
            $gizi = 'Beresiko Gizi Lebih';
        } elseif ($request->z_score_gizi >= -2 && $request->z_score_gizi <= 1) {
            $gizi = 'Gizi Baik';
        } elseif ($request->z_score_gizi >= -3 && $request->z_score_gizi < -2) {
            $gizi = 'Gizi Kurang';
        } elseif ($request->z_score_gizi < -3) {
            $gizi = 'Gizi Buruk';
        }

        $statistik->tinggi = $request->tinggi;
        $statistik->berat = $request->berat;
        $statistik->lingkar_kepala = $request->lingkar_kepala;
        $statistik->date = $request->date;
        $statistik->z_score_berat = $request->z_score_berat;
        $statistik->z_score_tinggi = $request->z_score_tinggi;
        $statistik->z_score_lingkar_kepala = $request->z_score_lingkar_kepala;
        $statistik->status_berat_badan = $status_berat_badan;
        $statistik->status_tinggi_badan = $status_tinggi_badan;
        $statistik->status_lingkar_kepala = $status_lingkar_kepala;
        $statistik->z_score_gizi = $request->z_score_gizi;
        $statistik->status_gizi = $gizi;

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
