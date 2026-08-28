<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** Proses registrasi — role dari hidden input, user otomatis aktif (FR-A1, FR-A3) */
    public function register(Request $request)
    {
        $roleInput = $request->input('role_hidden', 'player');
        $dbRole    = $roleInput === 'owner' ? 'owner' : 'user';

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms'    => ['accepted'],
        ];

        // Field nama usaha wajib diisi jika mendaftar sebagai Owner (FR-A4)
        if ($dbRole === 'owner') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => $validated['password'],
            'role'        => $dbRole,
            'no_telp'     => $validated['phone'],
            'nama_usaha'  => $dbRole === 'owner' ? $validated['business_name'] : null,
        ]);

        Auth::login($user);

        return $dbRole === 'owner'
            ? redirect()->route('owner.dashboard')
            : redirect()->route('player.dashboard');
    }
}
