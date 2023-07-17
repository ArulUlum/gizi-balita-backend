<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiBaseController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterUserController extends ApiBaseController
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $role
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Http\JsonResponse
     */
    private function register(Request $request, $role)
    {

        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'id_desa' => ['required', 'exists:data_desa,id'],
            'id_posyandu' => ['required', 'exists:data_posyandu,id'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal registrasi", $validator->errors());
        }
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            // Email exists in the database
            return response()->json(['message' => 'Email is registered']);
        }


        $role = Role::where('role', $role)->first();

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_desa' => $request->id_desa,
            'id_posyandu' => $request->id_posyandu,
        ]);

        $user->role()->associate($role);
        $user->push();
        return $this->successResponse("registrasi berhasil, silahkan log in");
    }

    public function registerOrangTua(Request $request)
    {
        $role = "ORANG_TUA";
        return $this->register($request, $role);
    }

    public function registerKaderPosyandu(Request $request)
    {
        $role = "KADER_POSYANDU";
        return $this->register($request, $role);
    }

    public function registerDesa(Request $request)
    {
        $role = "DESA";
        return $this->register($request, $role);
    }

    public function registerTenagaKesehatan(Request $request)
    {
        $role = "TENAGA_KESEHATAN";
        return $this->register($request, $role);
    }

    public function registerAdmin(Request $request)
    {
        $role = "ADMIN";
        return $this->register($request, $role);
    }
}
