<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DesaResource;
use App\Models\Desa;
use Illuminate\Http\Request;


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
}
