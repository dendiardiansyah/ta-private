<x-mail::message>
    # Pendaftaran Pengguna Baru

    Terdapat pengguna baru yang mendaftar dan membutuhkan persetujuan Anda:

    **Nama:** {{ $user->name }}
    **Email:** {{ $user->email }}
    **Role:** {{ $user->roles->first()->name ?? 'Tidak ada' }}

    Silakan klik tombol di bawah ini untuk menyetujui pengguna ini secara langsung atau kunjungi halaman Penerimaan
    Registrasi di Dashboard Admin.

    <x-mail::button :url="route('admin.users.approve', ['user' => $user->id])">
        Setujui Pengguna
    </x-mail::button>

    Terima kasih,<br>
    {{ config('app.name') }}
</x-mail::message>