<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anak;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DataAnakController extends Controller
{

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $validator = validator($request->all(), [
            'offset' => ['integer'],
            'limit' => ['integer']
        ]);
        if ($validator->fails()) {
            return $this->errorValidationResponse("validation  failed", $validator->errors());
        }
        $limit = $request->exists('limit') ? $request->limit : 10;
        $offset = $request->exists('offset') ? $request->offset : 0;

        $kaderPosyandu = User::getUser(Auth::user());
        if ($kaderPosyandu->role->role == "ORANG_TUA") {
            return $this->showOrangTua($request);
        }
        if ($kaderPosyandu->role->role == "DESA") {
            return $this->showDesa($request);
        }

        $total = $kaderPosyandu->posyandu->anak()->count();
        $anak = $kaderPosyandu->posyandu->anak();
        if ($request->exists('limit')) {
            $anak = $anak->limit($limit);
        }
        if ($request->exists('offset')) {
            $anak = $anak->offset($offset);
        }
        $anak = $anak->get();

        $laporan = [
            'berat' => $kaderPosyandu->posyandu->laporanBerat(),
            'tinggi' => $kaderPosyandu->posyandu->laporanTinggi(),
            'lingkar_kepala' => $kaderPosyandu->posyandu->laporanLingkarKepala(),
        ];

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $anak,
            'laporan' => $laporan,
        ];

        return view('dashboard', $response);
    }

    public function showOrangTua(Request $request)
    {
        $validator = validator($request->all(), [
            'offset' => ['integer'],
            'limit' => ['integer']
        ]);
        if ($validator->fails()) {
            return $this->errorValidationResponse("validation  failed", $validator->errors());
        }
        $limit = $request->exists('limit') ? $request->limit : 10;
        $offset = $request->exists('offset') ? $request->offset : 0;

        $orangTua = User::getUser(Auth::user());

        $total = $orangTua->anak()->count();
        $anak = $orangTua->anak();
        if ($request->exists('limit')) {
            $anak = $anak->limit($limit);
        }
        if ($request->exists('offset')) {
            $anak = $anak->offset($offset);
        }
        $anak = $anak->get();


        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $anak,
        ];

        return view('dashboard-orang-tua', $response);
    }

    public function showDesa(Request $request)
    {
        $validator = validator($request->all(), [
            'offset' => ['integer'],
            'limit' => ['integer']
        ]);
        if ($validator->fails()) {
            return $this->errorValidationResponse("validation  failed", $validator->errors());
        }
        $limit = $request->exists('limit') ? $request->limit : 10;
        $offset = $request->exists('offset') ? $request->offset : 0;

        $desa = User::getUser(Auth::user());

        $total = $desa->desa->anak()->count();
        $anak = $desa->desa->anak();
        if ($request->exists('limit')) {
            $anak = $anak->limit($limit);
        }
        if ($request->exists('offset')) {
            $anak = $anak->offset($offset);
        }
        $anak = $anak->get();
        $dataPosyandu = $desa->desa->posyandu;
        $lapPosyandu = collect([]);

        foreach ($dataPosyandu as $posyandu) {
            $laporan = [
                'berat' => $posyandu->laporanBerat(),
                'tinggi' => $posyandu->laporanTinggi(),
                'lingkar_kepala' => $posyandu->laporanLingkarKepala(),
            ];

            $lapPosyandu[$posyandu->nama] = $laporan;
        }
        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $anak,
            'lap_posyandu' => $lapPosyandu,
        ];

        return view('dashboard-desa', $response);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate(
            [
                'nama' => ['required', 'string', 'min:3'],
                'panggilan' => ['string'],
                'tanggal_lahir' => ['required', 'date'],
                /* 'tinggi' => ['required'], */
                /* 'berat' => ['required'], */
                /* 'lingkar_kepala' => ['required'], */
                'nama_orang_tua' => ['required', 'string'],
                'alamat' => ['string'],
                'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
                'image' => ['string']
            ]
        );

        $anak = new Anak;

        $anak->fill([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'nama_orang_tua' => $request->nama_orang_tua,
            'gender' => $request->gender,
            'image' => $request->image,
        ]);

        $anak->desa()->associate($user->desa);
        $anak->posyandu()->associate($user->posyandu);

        $anak->save();
        $anak->statistik()->create([
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'z_score' => $request->z_score,
            'date' => now(),
        ]);

        $anak->push();
        return redirect("/dashboard");
    }

    public function storeOrangTua(Request $request)
    {
        $user = Auth::user();
        $request->validate(
            [
                'nama' => ['required', 'string', 'min:3'],
                'panggilan' => ['string'],
                'tanggal_lahir' => ['required', 'date'],
                /* 'tinggi' => ['required'], */
                /* 'berat' => ['required'], */
                /* 'lingkar_kepala' => ['required'], */
                /* 'nama_orang_tua' => ['required', 'string'], */
                'alamat' => ['string'],
                'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
                'image' => ['string']
            ]
        );

        $anak = new Anak;

        $anak->fill([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $user->alamat,
            'nama_orang_tua' => $user->nama,
            'gender' => $request->gender,
            'image' => $request->image,
        ]);

        $anak->desa()->associate($user->desa);
        $anak->posyandu()->associate($user->posyandu);
        $anak->orangTua()->associate($user);

        $anak->save();
        $anak->statistik()->create([
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'z_score' => $request->z_score,
            'date' => now(),
        ]);

        $anak->push();
        return redirect("/dashboard");
    }
}
