<?php

namespace App\Http\Controllers\Api;

use App\Models\Anak;
use App\Models\User;
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
    public function indexWithOrangTua(Request $request)
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
        $anak = $orangTua->anak()->limit($limit)->offset($offset * $limit)->get();

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $anak,
        ];
        return $this->successResponse("data anak", $response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithKaderPosyandu(Request $request)
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

        $total = $kaderPosyandu->posyandu->anak()->count();
        $anak = $kaderPosyandu->posyandu->anak()->limit($limit)->offset($offset * $limit)->get();

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $anak,
        ];
        return $this->successResponse("data anak", $response);
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
            // 'tinggi' => ['required'],
            // 'berat' => ['required'],
            // 'lingkar_kepala' => ['required'],
            'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
            'image' => ['string'],
            // 'id_orang_tua' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data anak", $validator->errors());
        }

        $anak = Anak::create([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $user->alamat,
            // 'nama_orang_tua' => $user->nama,
            'gender' => $request->gender,
            'image' => $request->image,
            'id_posyandu' => $user->id_posyandu,
            'id_desa' => $user->id_desa,
            'id_orang_tua' => $user->id
            // 'berat_terakhir' => $request->berat,
            // 'tinggi_terakhir' => $request->tinggi,
            // 'lingkar_kepala_terakhir' => $request->lingkar_kepala,
        ]);

        // $anak->desa()->associate($user->desa);
        // $anak->posyandu()->associate($user->posyandu);
        // $anak->orangTua()->associate($user);

        $anak->save();
        // $anak->statistik()->create([
        //     'tinggi' => $request->tinggi,
        //     'berat' => $request->berat,
        //     'lingkar_kepala' => $request->lingkar_kepala,
        //     'date' => now(),
        // ]);


        // $anak->push();
        return $this->successResponse("success");
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
            // 'tinggi' => ['required'],
            // 'berat' => ['required'],
            /* 'lingkar_kepala' => ['required'], */
            // 'nama_orang_tua' => ['required', 'string'],
            'alamat' => ['string'],
            'gender' => ['required', Rule::in(['LAKI_LAKI', 'PEREMPUAN'])],
            'image' => ['string'],
            'id_orang_tua' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data anak", $validator->errors());
        }

        $anak = new Anak;

        $anak->fill([
            'nama' => $request->nama,
            'panggilan' => $request->panggilan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            // 'nama_orang_tua' => $request->nama_orang_tua,
            'gender' => $request->gender,
            'image' => $request->image,
            'id_orang_tua' => $request->id_orang_tua,
            'id_posyandu' => $user->id_posyandu,
            'id_desa' => $user->id_desa,
        ]);

        // $anak->desa()->associate($user->desa);
        // $anak->posyandu()->associate($user->posyandu);

        $anak->save();
        // $anak->statistik()->create([
        //     'tinggi' => $request->tinggi,
        //     'berat' => $request->berat,
        //     'lingkar_kepala' => $request->lingkar_kepala,
        //     'z_score_berat' => $request->z_score_berat,
        //     'z_score_tinggi' => $request->z_score_tinggi,
        //     'z_score_lingkar_kepala' => $request->z_score_lingkar_kepala,
        //     'date' => now(),
        // ]);

        // $anak->push();
        return $this->successResponse("success");
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

        return $this->successResponse("data anak", $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anak  $anak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anak $anak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anak  $anak
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anak $anak)
    {
        //
    }
}
