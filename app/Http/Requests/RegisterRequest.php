<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleInput = $this->input('role_hidden', 'player');
        $dbRole = $roleInput === 'owner' ? 'owner' : 'user';

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'regex:/^\d{12}$/'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-zA-Z]/',      // wajib mengandung huruf
                'regex:/[0-9]/',          // wajib mengandung angka
                'regex:/[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\\\/~`]/', // wajib karakter khusus
            ],
            'terms' => ['accepted'],
        ];

        // Field nama usaha wajib jika mendaftar sebagai Owner (FR-A4)
        if ($dbRole === 'owner') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
        }

        return $rules;
    }

    /** Pesan error berbahasa Indonesia */
    public function messages(): array
    {
        return [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'name.max'               => 'Nama lengkap maksimal 255 karakter.',
            'email.required'         => 'Alamat email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar, gunakan email lain.',
            'phone.required'         => 'Nomor HP/WhatsApp wajib diisi.',
            'phone.regex'            => 'Nomor HP harus terdiri dari tepat 12 digit angka (tanpa huruf atau simbol).',
            'password.required'      => 'Password wajib diisi.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.regex'         => 'Password harus mengandung kombinasi huruf, angka, dan karakter khusus (misal !@#$%^&*).',
            'terms.accepted'         => 'Anda harus menyetujui Syarat & Ketentuan.',
            'business_name.required' => 'Nama usaha lapangan wajib diisi untuk akun Owner.',
        ];
    }
}
