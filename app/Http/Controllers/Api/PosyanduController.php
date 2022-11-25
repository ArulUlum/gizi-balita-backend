<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posyandu;

class PosyanduController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = validator($request->all(), [
            'offset' => ['integer'],
            'limit' => ['integer'],
            'id_desa' => ['integer'],
        ]);

        $limit = $request->exists('limit') ? $request->limit : 10;
        $offset = $request->exists('offset') ? $request->offset : 0;

        if ($validator->fails()) {
            return $this->errorValidationResponse("validation  failed", $validator->errors());
        }

        $posyandu = new Posyandu;

        if ($request->exists('id_desa')) {
            $posyandu = $posyandu->where('id_desa', $request->id_desa);
        }

        $total = $posyandu->count();
        $posyandu = $posyandu->limit($limit)->offset($offset * $limit)->get();

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $posyandu,
        ];

        return $this->successResponse("data posyandu", $response);
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
            return $this->errorValidationResponse("gagal create data desa");
        }

        return $this->successResponse("success");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
