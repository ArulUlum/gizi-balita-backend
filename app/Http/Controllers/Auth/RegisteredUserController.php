<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeOrangTua(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'alamat' => ['required', 'string'],
            'id_desa' => ['required', 'exists:data_desa,id'],
            'id_posyandu' => ['required', 'exists:data_posyandu,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
            'id_desa' => $request->id_desa,
            'id_posyandu' => $request->id_posyandu,
        ]);

        $role = Role::where('role', 'ORANG_TUA')->first();
        $user->role()->associate($role);
        $user->push();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function storePosyandu(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'id_desa' => ['required', 'exists:data_desa,id'],
            'id_posyandu' => ['required', 'exists:data_posyandu,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_desa' => $request->id_desa,
            'id_posyandu' => $request->id_posyandu,
        ]);

        $role = Role::where('role', 'KADER_POSYANDU')->first();
        $user->role()->associate($role);
        $user->push();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
