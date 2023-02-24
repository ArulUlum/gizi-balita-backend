<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends ApiBaseController
{

    public function index()
    {
        $comment = Comment::with('user')->with('post')->get();

        foreach ($comment as $key => $data) {
            $response[$key] = [
                "comment_id" => $data->id,
                "post_id" => $data->post_id,
                "title_post" => $data->post->title,
                "content" => $data->post->content,
                "user_id" => $data->user_id,
                "nama" => $data->user->nama,
                "email" => $data->user->email,
                "comment" => $data->content,
                "time" => Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return $this->successResponse("Data Post", $response);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'user_id' => ['required', 'integer'],
            'post_id' => ['required', 'integer'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan TIdak Sesuai", $validator->errors());
        }

        if (User::where('id', $request->user_id)->doesntExist()) {
            return $this->errorNotFound("User Tidak Ditemukan");
        }

        if (Post::where('id', $request->post_id)->doesntExist()) {
            return $this->errorNotFound("Post Tidak Ditemukan");
        }

        $comment = new Comment();

        $comment->fill([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        $comment->save();

        return $this->successResponse("Data Berhasil Disimpan");
    }

    public function show($id)
    {
        $comment = Post::find($id)->comments->all();

        if (empty($comment)) {
            return $this->errorNotFound("Data Post tidak ditemukan");
        }

        foreach ($comment as $key => $data) {
            $user = User::find($data->user_id);

            $response[$key] = [
                "user_id" => $user->id,
                "nama" => $user->nama,
                "email" => $user->email,
                "comment_id" => $data->id,
                "content" => $data->content,
                "time" => Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('Y-m-d H:i:s'),
            ];
        }

        return $this->successResponse("Data Comment", $response);
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (empty($comment)) {
            return $this->errorNotFound("Data Comment tidak ditemukan");
        }

        $comment->delete();

        return $this->successResponse("Success Delete Data Post");
    }
}
