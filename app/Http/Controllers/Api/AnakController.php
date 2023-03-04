<?php

namespace App\Http\Controllers\Api;

use App\Models\Anak;
use App\Models\StatistikAnak;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AnakController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithOrangTua()
    {
        $orangTua = User::getUser(Auth::user());
        $anak = $orangTua->anak()->get();

        return $this->successResponse("data anak", $anak);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithKaderPosyandu()
    {
        $kaderPosyandu = User::getUser(Auth::user());
        $anak = $kaderPosyandu->posyandu->anak()->get();

        return $this->successResponse("data anak", $anak);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithOrangTua(Request $request)
    {
        $user = Auth::user();
        $validator = validator($request->all(), [
            'nama' => ['required', 'string', 'min:3'],
            'panggilan' => ['string'],
            'tanggal_lahir' => ['required', 'date'],
            'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
            'image' => ['string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Gagal Input Data Anak", $validator->errors());
        }

        $anak = Anak::create([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'gender' => $request->gender,
            'image' => $request->image,
            'id_posyandu' => $user->id_posyandu,
            'id_desa' => $user->id_desa,
            'id_orang_tua' => $user->id
        ]);

        $anak->save();

        return $this->successResponse("Success");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithKaderPosyandu(Request $request)
    {
        $user = Auth::user();
        $validator = validator($request->all(), [
            'nama' => ['required', 'string', 'min:3'],
            'panggilan' => ['string'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['string'],
            'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
            'image' => ['string'],
            'id_orang_tua' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Gagal Input Data Anak", $validator->errors());
        }

        $anak = new Anak;

        $anak->fill([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'gender' => $request->gender,
            'image' => $request->image,
            'id_orang_tua' => $request->id_orang_tua,
            'id_posyandu' => $user->id_posyandu,
            'id_desa' => $user->id_desa,
        ]);

        $anak->save();

        return $this->successResponse("Success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anak  $anak
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anak = Anak::findOrFail($id);

        $response = [
            'data' => $anak,
        ];

        return $this->successResponse("Data Anak", $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anak  $anak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $anak = Anak::findOrFail($id);
        $anak->fill($request->all());
        $anak->save();

        return $this->successResponse("Success Update Data Anak", $anak);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anak  $anak
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $statistik = StatistikAnak::where('id_anak', $id);
        $statistik->delete();

        $anak = Anak::findOrFail($id);
        $anak->delete();

        return $this->successResponse("Success Delete Data Anak");
    }

    public function exportDataAnakCSV(Request $request)
    {
        $validator = validator($request->all(), [
            'desa' => ['integer'],
            'bulan' => ['string'],
            'tahun' => ['string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Gagal Ekspor Data", $validator->errors());
        }

        $anak = Anak::all();
        $collection = [];

        foreach ($anak as $data) {
            foreach ($data->statistik as $statistik) {
                if (Carbon::parse($statistik->date)->month == $request->bulan && Carbon::parse($statistik->date)->year == $request->tahun) {
                    array_push($collection, $statistik);
                }
            }
        }

        if (empty($collection)) {
            return $this->errorNotFound("Data Export tidak tersedia di waktu tersebut");
        }

        $fileName = 'data-anak.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
            'Nama', 'JK', 'Tanggal Lahir', 'Nama Orang Tua', 'Posyandu',
            'Alamat', 'Tanggal Pengukuran', 'Berat', 'Tinggi', 'Lingkar Kepala',
            'BB/U', 'Z - BB/U', 'TB/U', 'Z - TB/U', 'LK/U', 'Z - LK/U'
        );
        // $columns = array('No', 'NIK', 'Nama', 'JK', 'Tanggal Lahir', 'Nama Orang Tua', 'Posyandu', 'Alamat', 'Tanggal Pengukuran', 'Berat', 'Tinggi', 'Lingkar Kepala', 'BB/U', 'Z - BB/U', 'TB/U', 'Z - TB/U', 'LK/U', 'Z - LK/U');
        // $columns = array('No', 'Nama', 'JK', 'Tanggal Lahir', 'Nama Orang Tua', 'Posyandu', 'Alamat');

        $callback = function () use ($collection, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($collection as $data) {

                $row['Nama'] = $data->anak->nama;
                $row['JK'] = $data->anak->gender;
                $row['Tanggal Lahir']  = $data->anak->tanggal_lahir;
                $row['Nama Orang Tua']  = $data->anak->orangTua->nama;
                $row['Posyandu']  = $data->anak->posyandu->nama;
                $row['Alamat']  = $data->anak->posyandu->alamat;

                $row['Tanggal Pengukuran']  = $data->date;
                $row['Berat']  = $data->berat;
                $row['Tinggi']  = $data->tinggi;
                $row['Lingkar Kepala']  = $data->lingkar_kepala;
                $row['BB/U']  = "Baik";
                $row['ZS - BB/U']  = $data->z_score_berat;

                $row['TB/U']  = "Baik";
                $row['ZS - TB/U']  = $data->z_score_tinggi;
                $row['LK/U']  = "Baik";
                $row['ZS - LK/U']  = $data->z_score_lingkar_kepala;

                fputcsv($file, array(
                    $row['Nama'], $row['JK'], $row['Tanggal Lahir'], $row['Nama Orang Tua'], $row['Posyandu'],
                    $row['Alamat'], $row['Tanggal Pengukuran'], $row['Berat'], $row['Tinggi'], $row['Lingkar Kepala'],
                    $row['BB/U'], $row['ZS - BB/U'], $row['TB/U'], $row['ZS - TB/U'], $row['LK/U'], $row['ZS - LK/U']
                ));
                // fputcsv($file, array($row['No'], $row['NIK'], $row['Nama'], $row['JK'], $row['Tanggal Lahir'], $row['Nama Orang Tua'], $row['Posyandu'], $row['Alamat'], $row['Tanggal Pengukuran'], $row['Berat'], $row['Tinggi'], $row['Lingkar Kepala'], $row['BB/U'], $row['ZS - BB/U'], $row['TB/U'], $row['ZS - TB/U'], $row['LK/U'], $row['ZS - LK/U']));
                // fputcsv($file, array($row['No'], $row['Nama'], $row['JK'], $row['Tanggal Lahir'], $row['Nama Orang Tua'], $row['Posyandu'], $row['Alamat']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
