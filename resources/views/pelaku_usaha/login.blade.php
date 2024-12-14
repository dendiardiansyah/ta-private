<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .element {
            background-image: url('/image/black.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;

        }
    </style>

</head>

<body class="element">
    <!-- Full height container -->
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="form-container shadow p-5 rounded bg-white">
            <h2 class="text-center mb-5 font">Login Pelaku Usaha</h2>
            <div class="d-flex justify-content-center mb-4">
                <a href="/">
                    <img src="{{ asset('image/admin.png') }}" alt="Logo" class="logo-img">
                </a>
            </div>
            <!-- Laravel Jetstream Login Form -->
            <form method="POST" action="{{ route('pelaku_usaha.login') }}">
                @csrf
                <!-- Nama -->
                <div class="mb-3">
                    <label for="nama" class="form-label font">Nama</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" value="{{ old('nama') }}" required autofocus>
                    @error('nama')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- Password with Eye Icon -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label font">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required>
                    <i class="fas fa-eye position-absolute" id="togglePassword" style="top: 44px; right: 10px; cursor: pointer;"></i>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- Remember Me -->
                <div class="mb-3">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label font">Remember me</label>
                    </div>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="btn btn-success w-100 font">Log in</button>
                <!-- Forgot Password -->
                <div class="mt-3">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font" style="text-decoration: none; color:green;">Forgot password?</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toggle Password Visibility -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>