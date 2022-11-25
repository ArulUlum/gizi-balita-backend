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
    public function index(Request $request)
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

        $desa = new Desa;
        $total = $desa->count();
        $desa = $desa->limit($limit)->offset($offset * $limit)->get();

        $data = DesaResource::collection($desa);

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $data,
        ];

        return $this->successResponse("data desa", $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(),[
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
    public function show(Desa $desa)
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
    public function update(Request $request, Desa $desa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Desa  $desa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Desa $desa)
    {
        //
    }
}
