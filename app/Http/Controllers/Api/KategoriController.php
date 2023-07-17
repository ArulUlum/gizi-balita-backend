<?php

namespace App\Http\Controllers\api;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Kategori::all();
        return $this->successResponse("Data Artikel", $response);
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
            'name' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan Tidak Sesuai", $validator->errors());
        }

        $kategori = Kategori::create([
            'name' => $request->name,
        ]);

        $kategori->save();

        return $this->successResponse("Data Berhasil Disimpan");
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
        $kategori = Kategori::findOrFail($id);
        $kategori->fill($request->all());
        $kategori->save();

        return $this->successResponse("Success Update Data", $kategori);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (empty($kategori)) {
            return $this->errorNotFound("Data tidak ditemukan");
        }

        $kategori->delete();

        return $this->successResponse("Success Delete Data");
    }
}
