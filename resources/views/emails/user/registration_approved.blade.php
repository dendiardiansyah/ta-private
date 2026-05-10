<x-mail::message>
    # Pendaftaran Disetujui

    Halo {{ $user->name }},

    Pendaftaran akun Anda telah disetujui oleh Admin. Sekarang Anda dapat masuk ke dalam sistem menggunakan akun yang
    telah didaftarkan.

    <x-mail::button :url="route('login')">
        Masuk Sekarang
    </x-mail::button>

    Terima kasih,<br>
    {{ config('app.name') }}
</x-mail::message>