<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends ApiBaseController
{

    public function index()
    {
        $comment = Comment::all();

        return $this->successResponse("Data Post", $comment);
    }

    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'id_user' => ['required', 'integer'],
            'id_post' => ['required', 'integer'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan TIdak Sesuai", $validator->errors());
        }

        if (User::where('id', $request->id_user)->doesntExist()) {
            return $this->errorNotFound("User Tidak Ditemukan");
        }

        if (Post::where('id', $request->id_post)->doesntExist()) {
            return $this->errorNotFound("Post Tidak Ditemukan");
        }

        $comment = new Comment();

        $comment->fill([
            'id_user' => $request->id_user,
            'id_post' => $request->id_post,
            'content' => $request->content,
        ]);

        $comment->save();

        return $this->successResponse("Data Berhasil Disimpan");
    }

    public function show($id)
    {
        $comment = DB::table('comments')->where('id_post', $id)->get();

        if (empty($comment)) {
            return $this->errorNotFound("Data Comment tidak ditemukan");
        }

        return $this->successResponse("Data Comment", $comment);
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
