<x-mail::message>
    # Halo, {{ $user->name }}

    Terima kasih telah mendaftar. Registrasi Anda saat ini sedang ditinjau oleh admin.
    Anda akan menerima email pemberitahuan lebih lanjut setelah akun Anda disetujui.

    Harap diperhatikan bahwa Anda belum dapat masuk (login) ke dalam sistem sampai akun Anda disetujui.

    Terima kasih,<br>
    {{ config('app.name') }}
</x-mail::message>