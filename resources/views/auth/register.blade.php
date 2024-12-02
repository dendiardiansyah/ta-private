<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - UpcycleHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/cssreg.css') }}">
</head>

<body>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center mb-5 font">Registrasi UpcycleHub</h2>
                <div class="d-flex justify-content-center mb-4"><img src="{{ asset('image/logomain.png') }}" alt="Logo" style="height: 80px; width: 80px;"></div>

                <!-- Jetstream Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="input-container mb-3">
                        <input type="text" name="name" id="name" required value="{{ old('name') }}">
                        <label for="name" class="label font">Nama Lengkap</label>
                        <div class="underline"></div>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="input-container mb-3">
                        <input type="text" name="email" id="email" required value="{{ old('email') }}">
                        <label for="email" class="label font">Email</label>
                        <div class="underline"></div>
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="input-container mb-3">
                        <input type="text" name="nomor_telepon" id="nomor_telepon" required value="{{ old('nomor_telepon') }}">
                        <label for="nomor_telepon" class="label font">Nomor Telepon</label>
                        <div class="underline"></div>
                        @error('nomor_telepon')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Alamat Penjemputan -->
                    <div class="input-container mb-3">
                        <input type="text" name="alamat_penjemputan" id="alamat_penjemputan" required value="{{ old('alamat_penjemputan') }}">
                        <label for="alamat_penjemputan" class="label font">Alamat Penjemputan</label>
                        <div class="underline"></div>
                        @error('alamat_penjemputan')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="input-container mb-3">
                        <div class="position-relative">
                            <input type="password" name="password" id="password" required>
                            <label for="password" class="label font">Password</label>
                            <div class="underline"></div>
                            <i class="fas fa-eye position-absolute" id="togglePassword" style="top: 10px; right: 10px; cursor: pointer;"></i>
                        </div>
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="input-container mb-3">
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <label for="password_confirmation" class="label font">Konfirmasi Password</label>
                        <div class="underline"></div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 font">Register</button>
                </form>

                <p class="text-center mt-3 font">
                    Sudah punya akun? <a class="font" href="{{ route('login') }}" style="text-decoration: none; color: green;">Login di sini</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>