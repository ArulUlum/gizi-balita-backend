<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends ApiBaseController
{

    public function index()
    {
        $post = Post::all();

        return $this->successResponse("Data Post", $post);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'id_user' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Input Masih Tidak Sesuai", $validator->errors());
        }

        $user = DB::table('users')->where('id', $request->id_user)->get();

        if (empty($user)) {
            return $this->errorNotFound("User Tidak Ditemukan");
        } else if (!$user->where('id_role', 3)->first()) {
            return $this->errorUnauthorizedResponse("User Tidak Memiliki Hak Akses");
        }

        $post = new Post();

        $post->fill([
            'id_user' => $request->id_user,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->save();

        return $this->successResponse("Data Post Berhasil Disimpan");
    }


    public function showByOrangTua($id)
    {
        $post = DB::table('posts')->where('id_user', $id)->get();

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        return $this->successResponse("Data Post", $post);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        return $this->successResponse("Data Post", $post);
    }


    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        $validator = validator($request->all(), [
            'id_user' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Input Masih Tidak Sesuai", $validator->errors());
        }

        if (User::where('id', $request->id_user)->doesntExist()) {
            return $this->errorNotFound("User Tidak Ditemukan");
        }

        $post->fill([
            'id_user' => $request->id_user,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->save();

        return $this->successResponse("Success Update Data Post");
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        $post->delete();

        return $this->successResponse("Success Delete Data Post");
    }
}
