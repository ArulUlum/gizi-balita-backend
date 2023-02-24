<?php

namespace App\Http\Controllers\Api;

use App\Models\Anak;
use App\Models\StatistikAnak;
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
        $orangTua = User::getUser(Auth::user());

        $total = $orangTua->anak()->count();
        $anak = $orangTua->anak()->get();

        $response = [
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
        $kaderPosyandu = User::getUser(Auth::user());

        $total = $kaderPosyandu->posyandu->anak()->count();
        $anak = $kaderPosyandu->posyandu->anak()->get();

        $response = [
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
}
