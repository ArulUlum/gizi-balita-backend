<?php

namespace App\Http\Controllers\Api;

use App\Models\Anak;
use App\Models\StatistikAnak;
use App\Http\Resources\StatistikResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use DB;


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
        $fiveNineMonthsAgo = Carbon::now()->subMonths(60)->format('Y-m-d');
        $anak = $orangTua->anak()->whereDate('tanggal_lahir', '>', $fiveNineMonthsAgo)->orderBy('tanggal_lahir', 'asc')->get();

        foreach ($anak as $key => $item) {
            $item->anak_ke = $key + 1;
        }

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
        $fiveNineMonthsAgo = Carbon::now()->subMonths(60)->format('Y-m-d');
        $anak = $kaderPosyandu->posyandu->anak()->whereDate('tanggal_lahir', '>', $fiveNineMonthsAgo)->get();

        foreach ($anak as $item){
            $statistik = StatistikAnak::find($item->id);
            if (empty($statistik)){
                $item->status_berat_terakhir = NULL;
                $item->status_tinggi_terakhir = NULL;
                $item->status_lingkaran_kepala_terakhir = NULL;
                $item->status_gizi_terakhir = NULL;
                continue;
            }
            $statistik = $statistik->orderBy('date', 'desc')->first();
            $item->status_berat_terakhir = $statistik->status_berat_badan;
            $item->status_tinggi_terakhir = $statistik->status_tinggi_badan;
            $item->status_lingkaran_kepala_terakhir = $statistik->status_lingkar_kepala;
            $item->status_gizi_terakhir = $statistik->status_gizi;
        }

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
    public function storeWithKaderPosyanduExcel(Request $request)
    {
        $user = Auth::user();
        $validator = validator($request->all(), [
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);
        if ($validator->fails()) {
            return $this->errorValidationResponse("validation failed", $validator->errors());
        }
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray([], $path);
        $z_score = new HitungZScoreController();

        if (count($data) > 0 && count($data[0]) > 0) {
            foreach ($data[0] as $row) {
                if ($row[0] != 'No' and $row[0] != ''){
                    $id_ortu = DB::table('users')->where('nama', '=', $row[6])->value('id');
                    
                    $gender = '';
                    if ($row[5] == 'L' or $row[5] == 'l'){
                        $gender = 'LAKI_LAKI';
                    } else if ($row[5] == 'P' or $row[5] == 'p'){
                        $gender = 'PEREMPUAN';
                    }
                    $anak = Anak::create([
                        'nama' => $row[1],
                        'panggilan' => $row[2],
                        'tanggal_lahir' => DateTime::createFromFormat('Y-m-d', $row[3]),
                        'alamat' => $row[4],
                        'gender' => $gender,
                        'id_orang_tua' => $id_ortu,
                        'id_posyandu' => $user->id_posyandu,
                        'id_desa' => $user->id_desa,
                    ]);
                    $anak->save();

                    $id_anak = DB::table('data_anak')->where('nama', '=', $row[1])->value('id');

                    $hitung_berat = $z_score->HitungZScoreBerat($row[8]);
                    $hitung_tinggi = $z_score->HitungZScoreTinggi($row[9]);
                    $hitung_LiLA = $z_score->HitungZScoreLiLA($row[10]);

                    $statistik = StatistikAnak::create([
                        'id_anak' => $id_anak,
                        'date' => DateTime::createFromFormat('Y-m-d', $row[7]),
                        'berat' => $row[8],
                        'tinggi' => $row[9],
                        'lingkar_kepala' => $row[10],
                        'z_score_berat' => $hitung_berat['z_score_berat'],
                        'z_score_tinggi' => $hitung_tinggi['z_score_tinggi'],
                        'z_score_lingkar_kepala' => $hitung_LiLA['z_score_lingkar_kepala'],
                        'status_berat_badan' => $hitung_berat['status_berat_badan'],
                        'status_tinggi_badan' => $hitung_tinggi['status_tinggi_badan'],
                        'status_lingkar_kepala' => $hitung_LiLA['status_lingkar_kepala'],
                    ]);
            
                    $statistik->save();
                }
            }
        }

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

        return $this->successResponse("Data Anak", $anak);
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

        $anak = Anak::where('id_desa', $request->desa);
        $collection = [];
        if ($request->id != NULL){
            $anak = $anak->where('id_posyandu', $request->id);
        }
        $anak = $anak->get();
        foreach ($anak as $data) {
            foreach ($data->statistik as $statistik) {
                if (Carbon::parse($statistik->date)->month == $request->bulan && Carbon::parse($statistik->date)->year == $request->tahun) {
                    array_push($collection, $statistik);
                }
            }
        }

        if (empty($collection)) {
            return $this->errorNotFound("Data Export tidak tersedia");
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
                $row['BB/U']  = $this->beratBadan($data->z_score_berat);
                $row['ZS - BB/U']  = $data->z_score_berat;
                $row['TB/U']  = $this->tinggiBadan($data->z_score_tinggi);
                $row['ZS - TB/U']  = $data->z_score_tinggi;
                $row['LK/U']  = $this->lingkarKepala($data->z_score_lingkar_kepala);
                $row['ZS - LK/U']  = $data->z_score_lingkar_kepala;

                fputcsv($file, array(
                    $row['Nama'], $row['JK'], $row['Tanggal Lahir'], $row['Nama Orang Tua'], $row['Posyandu'],
                    $row['Alamat'], $row['Tanggal Pengukuran'], $row['Berat'], $row['Tinggi'], $row['Lingkar Kepala'],
                    $row['BB/U'], $row['ZS - BB/U'], $row['TB/U'], $row['ZS - TB/U'], $row['LK/U'], $row['ZS - LK/U']
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function beratBadan($ZScoreBerat)
    {
        if ($ZScoreBerat <= -3) {
            return 'Sangat Kurus';
        } else if ($ZScoreBerat > -3 && $ZScoreBerat <= -2) {
            return 'Kurus';
        } else if ($ZScoreBerat > -2 && $ZScoreBerat <= 2) {
            return 'Normal';
        } else if ($ZScoreBerat > 2) {
            return 'Gemuk';
        }
    }

    public function tinggiBadan($ZScoreTinggi)
    {
        if ($ZScoreTinggi <= -3) {
            return 'Sangat Pendek';
        } else if ($ZScoreTinggi > -3 && $ZScoreTinggi <= -2) {
            return 'Pendek';
        } else if ($ZScoreTinggi > -2 && $ZScoreTinggi <= 2) {
            return 'Normal';
        } else if ($ZScoreTinggi > 2) {
            return 'Tinggi';
        }
    }

    public function lingkarKepala($ZScoreLingkarKepala)
    {
        if ($ZScoreLingkarKepala > 2) {
            return 'Makrosefali';
        } else if ($ZScoreLingkarKepala > -2 && $ZScoreLingkarKepala <= 2) {
            return 'Normal';
        } else if ($ZScoreLingkarKepala < -2) {
            return 'Mikrosefali';
        }
    }
}
