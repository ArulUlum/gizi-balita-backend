<?php

namespace App\Http\Controllers\Api;

use App\Models\Artikel;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends ApiBaseController
{

    public function index()
    {
        $response = Artikel::all();
        return $this->successResponse("Data Artikel", $response);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'judul' => ['required'],
            'kategori' => ['required'],
            'penulis' => ['required'],
            'content' => ['required'],
            'image' => ['file', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan Tidak Sesuai", $validator->errors());
        }

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move('img', $imageName);

        $artikel = Artikel::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'image' => $imageName,
            'penulis' => $request->penulis,
            'content' => $request->content,
        ]);

        $artikel->save();

        return $this->successResponse("Data Berhasil Disimpan");
    }

    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        if (empty($artikel)) {
            return $this->errorNotFound("Data Artikel tidak ditemukan");
        }
        return $this->successResponse("Data Artikel", $artikel);
    }


    public function update(Request $request, $id)
    {   
        $artikel = Artikel::find($id);
        if (empty($artikel)) {
            return $this->errorNotFound("Data Artikel tidak ditemukan");
        }
        $validator = validator($request->all(), [
            'judul' => ['required'],
            'kategori' => ['required'],
            'penulis' => ['required'],
            'content' => ['required'],
            'image' => ['file', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan Tidak Sesuai", $validator->errors());
        }

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $artikel->image = $imageName;
        }

        $artikel->judul = $request->judul;
        $artikel->kategori = $request->kategori;
        $artikel->penulis = $request->penulis;
        $artikel->content = $request->content;
        $artikel->save();

        return $this->successResponse("Success Update Data Artikel", $artikel);
    }

    public function destroy($id)
    {
        $artikel = Artikel::find($id);

        if (empty($artikel)) {
            return $this->errorNotFound("Data Artikel tidak ditemukan");
        }

        $artikel->delete();

        return $this->successResponse("Success Delete Data Artikel");
    }

    public function getImage($id)
    {
        $artikel = Artikel::find($id);
        if (empty($artikel)) {
            return $this->errorNotFound("Data Artikel tidak ditemukan");
        }
        $imageUrl = asset('img/' . $artikel->image);
        return $this->successResponse('image_url', $imageUrl);
    }
}
