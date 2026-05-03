<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->getKey())],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'alamat' => ['nullable', 'string', 'max:65535'],
            'alamat_penjemputan' => ['nullable', 'string', 'max:65535'],
            'nomor_telepon' => ['nullable', 'string', 'max:15'],
        ])->validateWithBag('updateProfileInformation');

        $alamat = $input['alamat'] ?? $input['alamat_penjemputan'] ?? null;
        $nomorTelepon = $input['nomor_telepon'] ?? null;

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input, $alamat, $nomorTelepon);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'alamat' => $alamat ?? $user->alamat,
                'nomor_telepon' => $nomorTelepon ?? $user->nomor_telepon,  // Menggunakan nilai lama jika tidak ada input
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input, ?string $alamat = null, ?string $nomorTelepon = null): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'alamat' => $alamat ?? $user->alamat,
            'nomor_telepon' => $nomorTelepon ?? $user->nomor_telepon,
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
