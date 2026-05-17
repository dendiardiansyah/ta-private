<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Role;
use App\Mail\AdminRegistrationNotification;
use App\Mail\UserRegistrationPending;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        // Jika email sudah pernah mendaftar dan ditolak, hapus data lama agar bisa mendaftar lagi
        $existingRejectedUser = User::where('email', $input['email'])
            ->where('status', 'rejected')
            ->first();

        if ($existingRejectedUser) {
            $existingRejectedUser->roles()->detach();
            $existingRejectedUser->delete();
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => ['required', 'string', 'in:Nasabah,Admin,Pelaku Usaha,Petugas Penjemputan'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $roleNameString = $input['role'];
        $roleSlugMap = [
            // Existing DB/seed uses role name `user` for Nasabah.
            'Nasabah' => 'user',
            'Admin' => 'admin',
            'Pelaku Usaha' => 'pelaku_usaha',
            'Petugas Penjemputan' => 'petugas',
        ];
        $roleSlug = $roleSlugMap[$roleNameString];

        $status = 'approved';
        if ($roleSlug !== 'user') {
            $status = 'pending';
        }

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'status' => $status,
        ]);

        $roleModel = Role::query()->firstOrCreate(['name' => $roleSlug]);
        $user->roles()->attach($roleModel->id);
        $user->load('roles');

        if ($status === 'pending') {
            $admin = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->where('status', 'approved')->inRandomOrder()->first();

            if ($admin) {
                Mail::to($admin->email)->send(new AdminRegistrationNotification($user));
            }

            Mail::to($user->email)->send(new UserRegistrationPending($user));
        }

        return $user;
    }
}

