<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends ApiBaseController
{
    public function index(Request $request)
    {
        $user = Auth::user();
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

        $orangtua = new User();

        $orangtua = $orangtua->limit($limit)->offset($offset * $limit)->where('id_role', 3)->where('id_desa', $user->id_desa)->where('id_posyandu', $user->id_posyandu)->get();
        $total = $orangtua->count();

        $response = [
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
            'data' => $orangtua,
        ];

        return $this->successResponse("data orang tua", $response);
    }
}
