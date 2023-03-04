<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrangTuaController extends ApiBaseController
{
    public function index()
    {
        $user = Auth::user();

        $orangtua = new User();
        $orangtua = $orangtua->where('id_role', 3)->where('id_desa', $user->id_desa)->where('id_posyandu', $user->id_posyandu)->get();

        return $this->successResponse("data orang tua", $orangtua);
    }
}
