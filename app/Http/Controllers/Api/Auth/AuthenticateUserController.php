<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AuthenticateUserController extends ApiBaseController
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $role
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function login(Request $request, $role)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal login", $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $data = [
                'token' => [
                    'type' => "Bearer",
                    'value' => $user->createToken('API Token')->plainTextToken,
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->nama,
                    'email' => $user->email,
                    'id_desa' => $user->desa->id,
                    'id_posyandu' => $user->posyandu->id,
                    'role' => $user->role->role
                ],
            ];

            if ($user->role->role != $role) {
                return $this->errorValidationResponse("periksa kembail email dan password");
            }
            return $this->successResponse("berhasil login", $data);
        }

        return $this->errorValidationResponse("periksa kembail email dan password");
    }

    public function loginbaru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("gagal login", $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $data = [
                'token' => [
                    'type' => "Bearer",
                    'value' => $user->createToken('API Token')->plainTextToken,
                ],
                'user' => [
                    'id' => $user->id,
                    'name' => $user->nama,
                    'email' => $user->email,
                    'id_desa' => $user->desa->id,
                    'id_posyandu' => $user->posyandu->id,
                    'role' => $user->role->role
                ],
            ];

            return $this->successResponse("berhasil login", $data);
        }

        return $this->errorValidationResponse("periksa kembail email dan password");
    }

    public function loginOrangTua(Request $request)
    {
        return $this->login($request, "ORANG_TUA");
    }

    public function loginKaderPosyandu(Request $request)
    {
        return $this->login($request, "KADER_POSYANDU");
    }

    public function loginDesa(Request $request)
    {
        return $this->login($request, "DESA");
    }

    public function loginTenagaKesehatan(Request $request)
    {
        return $this->login($request, "TENAGA_KESEHATAN");
    }

    public function loginAdmin(Request $request)
    {
        return $this->login($request, "ADMIN");
    }
}
