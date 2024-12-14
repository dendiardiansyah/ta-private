<x-app-layout>
    <style>

    </style>
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-4">
                <img src="{{ asset('image/logodash.png') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-4">
                <p class="font fw-bold">Halloo Selamat Datang, {{ Auth::user()->name }}!</p>
                <h1>
                    <p class="font fw-bold" style="font-size:40px;">Total Poin Anda <span class="text-success">{{ Auth::user()->total_poin }}</span></p>
                </h1>
                <c-button class="c-button c-button--gooey mt-3" onclick="window.location.href='{{ route('penjemputan') }}'">
                    Mulai Sekarang
                    <div class="c-button__blobs">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </c-button>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: block; height: 0; width: 0;">
                    <defs>
                        <filter id="goo">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
                            <feBlend in="SourceGraphic" in2="goo"></feBlend>
                        </filter>
                    </defs>
                </svg>
            </div>
        </div>

        <div class="video-section mt-5 ">
            <h2 class="text-center mb-4 text-success test" style="font-size: 40px;">UpcycleHub</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="video-wrapper">
                        <video width="100%" controls autoplay muted>
                            <source src="{{ asset('image/banksampah.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berita Section -->
        <div class="news-section mt-5">
            <h2 class="text-center mb-4 text-success test font-weight:bold;">Berita Terkini</h2>
            <div class="row">
                @foreach ($articles as $index => $article)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-success d-flex flex-column">
                        <img src="{{ $article['urlToImage'] ?? asset('image/default-news.jpg') }}" class="card-img-top" alt="News Image">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title title">{{ $article['title'] }}</h5>
                            <p class="card-text">{{ Str::limit($article['description'], 100) }}</p>
                            <a href="{{ $article['url'] }}" target="_blank" class="btn btn-outline-success mt-auto">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                @if (($index + 1) % 4 === 0 && $index + 1 !== count($articles))
            </div>
            <div class="row">
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- CSS untuk Border Hijau pada Video -->
    <style>
        .video-wrapper {
            border: 5px solid #28a745;
            /* Border hijau */
            border-radius: 10px;
            /* Menambahkan border radius (opsional) */
            padding: 10px;
            /* Memberikan jarak antara video dan border */
        }
    </style>
</x-app-layout>