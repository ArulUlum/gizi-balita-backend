<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posyandu;

class PosyanduController extends ApiBaseController
{
    public function index(Request $request)
    {
        $posyandu = Posyandu::all();

        return $this->successResponse("data posyandu", $posyandu);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'id_desa' => ['exists:data_desa,id', 'required'],
            'nama' => ['string', 'required'],
            'alamat' => ['string', 'required'],
            'latitude' => ['string'],
            'longitude' => ['string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal input data posyandu", $validator->errors());
        }

        $posyandu = Posyandu::make([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $posyandu->desa()->associate($request->id_desa);

        if (!($posyandu->save())) {
            return $this->errorValidationResponse("gagal create data Posyandu");
        }

        return $this->successResponse("success");
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        try{
            $posyandu = Posyandu::find($id);

            if (empty($posyandu)) {
                return $this->errorNotFound("Posyandu tidak ditemukan");
            }

            $posyandu->delete();

            return $this->successResponse("Success Delete Posyandu");
        } catch (\Exception $e){
            return $this->errorValidationResponse("Error Delete Posyandu", $e->getMessage());
        }
    }
}
