<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DesaResource;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesaController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $desa = Desa::all();

        return $this->successResponse("data desa", $desa);
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
            'nama' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data desa", $validator->errors());
        }

        $desa = Desa::make([
            'name'  => $request->nama
        ]);

        if (!($desa->save())) {
            return $this->errorValidationResponse("gagal create data desa");
        }

        return $this->successResponse("success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $desa = Desa::findOrFail($id);

        if (empty($desa)) {
            return $this->errorNotFound("Desa tidak ditemukan");
        }

        $desa->delete();

        return $this->successResponse("Success Delete Desa");
    }

    public function getStatistikDesa($id)
    {
        $desa = Desa::find($id);

        if (empty($desa)) {
            return $this->errorNotFound("Desa tidak ditemukan");
        }

        $response = [];
        $all_posyandu = $desa->posyandu;
        $latestStatistik = null;

        foreach ($all_posyandu as $key => $posyandu) {

            $response[$key] = [
                "id_posyandu" => $posyandu->id,
                "nama_posyandu" => $posyandu->nama,
                "jumlah_anak" => 0,
                "berat_badan" => [
                    'gemuk' => 0,
                    'normal' => 0,
                    'kurus' => 0,
                    'sangat_kurus' => 0,
                ],
                "tinggi_badan" => [
                    'tinggi' => 0,
                    'normal' => 0,
                    'pendek' => 0,
                    'sangat_pendek' =>  0,
                ],
                "lingkar_kepala" => [
                    'makrosefali' => 0,
                    'normal' => 0,
                    'mikrosefali' => 0,
                ],
            ];

            if (!empty($posyandu->anak)) {
                $allLatsStatistik = [];
                foreach ($posyandu->anak as $anak) {
                    $latestStatistik = DB::table('data_statistik_anak')->where('id_anak', $anak->id)->latest('created_at')->first();
                    $allLatsStatistik[] = $latestStatistik;
                }

                $response[$key] = [
                    "id_posyandu" => $posyandu->id,
                    "nama_posyandu" => $posyandu->nama,
                    "jumlah_anak" => $posyandu->jumlahAnak(),
                    "berat_badan" => $posyandu->laporanBerat($allLatsStatistik),
                    "tinggi_badan" => $posyandu->laporanTinggi($allLatsStatistik),
                    "lingkar_kepala" => $posyandu->laporanLingkarKepala($allLatsStatistik),
                ];
            }

        };

        return $this->successResponse("Data Statistik Desa", $response);
    }
}
