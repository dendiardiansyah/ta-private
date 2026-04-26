<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UpcycleHub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <style>
        .custom-navbar {
            display: flex;
            align-items: center;
            gap: 15px;

        }

        .nav-link {
            color: #000;
            text-decoration: none;
            transition: all 0.1s ease;

        }

        .nav-link:hover {
            color: green;
            transform: scale(1.1);
        }

        .separator {
            pointer-events: none;

        }

        .u {
            font-size: 70px;
            color: #0e6e36;
        }

        .element {
            background-image: url('image/black.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;

        }
    </style>
</head>

<body class="bg-light element">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="">
                <img class="logomain ms-4" src="{{ asset('image/logomain.png') }}" alt="UpcycleHub Logo">
            </div>
            <nav class="me-4 font custom-navbar">
                @auth
                <a href="{{ route('dashboard') }}" class="text-dark me-3 nav-link">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="  nav-link" style="text-decoration: none;">Login</a>
                <a href="#" class="text-dark ">|</a>
                <a href="{{ route('register') }}" class=" nav-link" style="text-decoration: none;">Register</a>
                <a href="#" class="text-dark ">|</a>
                <a href="{{ route('pelaku_usaha.login') }}" class="nav-link" style="text-decoration: none;">Pelaku Usaha</a>
                @endauth
            </nav>
        </div>

        <!-- Main Content Section -->
        <div class="row align-items-center ms-5" style="min-height: 80vh;">
            <div class="col-md-6 mb-4 mb-md-0 text-content">
                <h1 class="fw-bold my-5 font u">UPYCLEHUB</h1>
                <h2 class="fs-4 mb-5 font">“Hijaukan Bumi, Hemat Energi, <br> Mulai dari Sekarang!”</h2>
                <button class="button" style="--clr: #7808d0" onclick="window.location.href='{{ route('register') }}'">
                    <span class="button__icon-wrapper">
                        <svg viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="button__icon-svg" width="10">
                            <path d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z" fill="currentColor"></path>
                        </svg>
                        <svg viewBox="0 0 14 15" fill="none" width="10" xmlns="http://www.w3.org/2000/svg" class="button__icon-svg button__icon-svg--copy">
                            <path d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z" fill="currentColor"></path>
                        </svg>
                    </span>
                    Explore All
                </button>
            </div>

            <div class="col-md-6 text-center d-flex" style="min-height: 80vh;">
                <img src="{{ asset('image/animasi.png') }}" alt="Illustration of recycling and eco-friendly products" class="img-fluid align-self-end">
            </div>
        </div>




    </div>
    </div>

</body>

</html>