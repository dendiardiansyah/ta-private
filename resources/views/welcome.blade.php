<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UpcycleHub</title>
    <!-- Include Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
</head>

<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="">
                <img class="logomain ms-4" src="{{ asset('image/logomain.png') }}" alt="UpcycleHub Logo">
            </div>
            <nav class="me-4 font">
                @auth
                <a href="{{ route('dashboard') }}" class="text-dark me-3">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-dark me-3" style="text-decoration: none;">Login</a>
                <a href="#" class="text-dark me-3">|</a>
                <a href="{{ route('register') }}" class="text-dark" style="text-decoration: none;">Register</a>
                @endauth
            </nav>
        </div>

        <!-- Main Content Section -->
        <div class="row align-items-center ms-5" style="min-height: 80vh;">
            <div class="col-md-6 mb-4 mb-md-0 text-content">
                <h1 class="fw-bold my-5 font">UPYCLEHUB</h1>
                <h2 class="fs-4 mb-5 font">“Hijaunkan Bumi, Hemat Energi, <br> Mulai dari Sekarang!”</h2>
                <!-- Button with class button1 to trigger animation -->


                <button class="button" style="--clr: #7808d0" onclick="window.location.href='{{ route('register') }}'">
                    <span class="button__icon-wrapper">
                        <svg
                            viewBox="0 0 14 15"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="button__icon-svg"
                            width="10">
                            <path
                                d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"
                                fill="currentColor"></path>
                        </svg>

                        <svg
                            viewBox="0 0 14 15"
                            fill="none"
                            width="10"
                            xmlns="http://www.w3.org/2000/svg"
                            class="button__icon-svg button__icon-svg--copy">
                            <path
                                d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    Explore All
                </button>


            </div>

            <div class="col-md-6 text-center">
                <img src="{{ asset('image/logo.png') }}" alt="Illustration of recycling and eco-friendly products" class="img-fluid">
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>