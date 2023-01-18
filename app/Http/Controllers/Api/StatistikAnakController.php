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
        $user = User::getUser(Auth::user());

        $validator = validator($request->all(), [
            'id_anak' => ['required', 'integer', 'exists:data_anak,id'],
            'tinggi' => ['required'],
            'berat' => ['required'],
            /* 'lingkar_kepala' => ['required'], */
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data anak", $validator->errors());
        }


        // $anak = $user->posyandu->anak()->find($request->id_anak);

        // if ($user->role->name == 'ORANG_TUA') {
        //     $anak = $user->anak;
        // }

        $anak = Anak::find($request->id_anak);

        if (empty($anak)) {
            return $this->errorUnauthorizedResponse("doesn't belong to this account");
        }

        $statistik = $anak->statistik()->create([
            'id_anak' => $request->id_anak,
            'tinggi' => $request->tinggi,
            'berat' => $request->berat,
            'lingkar_kepala' => $request->lingkar_kepala,
            'date' => $request->date,
            'z_score_berat' => $request->z_score_berat,
            'z_score_tinggi' => $request->z_score_tinggi,
            'z_score_lingkar_kepala' => $request->z_score_lingkar_kepala,
        ]);

        $response = new StatistikResource($statistik);

        return $this->successResponse("berhasil input statistik anak", $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StatistikAnak  $statistikAnak
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        // $user = User::getUser(Auth::user());

        // $anak = $user->posyandu->anak()->find($id);

        // if ($user->role->name == 'ORANG_TUA') {
        //     $anak = $user->anak()->find($id);
        // }

        $anak = Anak::find($id);

        if (empty($anak)) {
            return $this->errorUnauthorizedResponse("doesn't belong to this account");
        }

        $statistik = $anak->statistik()->get();
        $response = StatistikResource::collection($statistik);


        return $this->successResponse("list statistik anak", $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StatistikAnak  $statistikAnak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StatistikAnak $statistikAnak)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatistikAnak  $statistikAnak
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatistikAnak $statistikAnak)
    {
        //
    }
}
