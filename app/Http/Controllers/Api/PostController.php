<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends ApiBaseController
{

    public function index()
    {
        $post = Post::with('user')->get();
        $response = [];

        foreach ($post as $key => $data) {
            $response[$key] = [
                "post_id" => $data->id,
                "user_id" => $data->user->id,
                "nama" => $data->user->nama,
                "role" => $data->user->role->role,
                "title" => $data->title,
                "content" => $data->content,
                "time" => Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return $this->successResponse("Data Post", $response);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'content' => ['string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Input Masih Tidak Sesuai", $validator->errors());
        }

        $user = DB::table('users')->where('id', $request->user_id)->get();

        if (empty($user)) {
            return $this->errorNotFound("User Tidak Ditemukan");
        } else if (!$user->where('id_role', 3)->first()) {
            return $this->errorUnauthorizedResponse("User Tidak Memiliki Hak Akses");
        }

        $post = new Post();

        $post->fill([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->save();

        return $this->successResponse("Data Post Berhasil Disimpan");
    }


    public function showByOrangTua($id)
    {
        $user = User::find($id)->post->all();

        if (empty($user)) {
            return $this->errorNotFound("Data Orang Tua tidak ditemukan");
        }

        $response = [];

        foreach ($user as $key => $data) {
            $response[$key] = [
                "post_id" => $data->id,
                "title" => $data->title,
                "content" => $data->content,
                "time" => Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return $this->successResponse("Data Post", $response);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        $user = User::find($post->user_id);

        $response = [
            "user_id" => $user->id,
            "nama" => $user->nama,
            "role" => $user->role->role,
            "title" => $post->title,
            "content" => $post->content,
            "time" => Carbon::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('Y-m-d H:i:s')
        ];

        return $this->successResponse("Data Post", $response);
    }


    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if (empty($post)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        $validator = validator($request->all(), [
            'user_id' => ['required', 'integer'],
            'title' => ['required', 'string'],
            'content' => ['string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Input Masih Tidak Sesuai", $validator->errors());
        }

        if (User::where('id', $request->user_id)->doesntExist()) {
            return $this->errorNotFound("User Tidak Ditemukan");
        }

        $post->fill([
            'user_id' => $request->user_id,
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

    public function changeobesitas(){
        $obe = DB::table('data_statistik_anak')->where('status_berat_badan', 'Obesitas')->update(array('status_berat_badan' => 'Gemuk'));

        return $this->successResponse("Success Data");
    }

    public function changestatusLK(){
        $LK1 = DB::table('data_statistik_anak')->where('status_lingkar_kepala', 'Makrosefalus')->update(array('status_lingkar_kepala' => 'Makrosefali'));
        $LK2 = DB::table('data_statistik_anak')->where('status_lingkar_kepala', 'Microcephaly')->update(array('status_lingkar_kepala' => 'Mikrosefali'));

        return $this->successResponse("Success Data");
    }
}
