<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Validasi input termasuk alamat_penjemputan dan nomor_telepon

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            // 'alamat_penjemputan' => ['required', 'string', 'max:255'],
            // 'nomor_telepon' => ['required', 'string', 'max:15'], // Anda bisa menyesuaikan panjang maksimal nomor telepon
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();


        // Membuat user baru termasuk alamat_penjemputan dan nomor_telepon
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => 'user',
            'password' => Hash::make($input['password']),
            // 'alamat_penjemputan' => $input['alamat_penjemputan'], // Menyimpan alamat penjemputan
            // 'nomor_telepon' => $input['nomor_telepon'], // Menyimpan nomor telepon
        ]);
    }
}
