<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** Proses registrasi — role dari hidden input, user otomatis aktif (FR-A1, FR-A3) */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $roleInput = $request->input('role_hidden', 'player');
        $dbRole    = $roleInput === 'owner' ? 'owner' : 'user';

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

