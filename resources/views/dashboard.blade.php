<x-app-layout>
    <style>
        .gold {
            color: #FFD700;
        }

        .news-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .news-image {
            height: 180px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .news-card:hover .news-image {
            transform: scale(1.05);
        }

        .news-title {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .news-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .news-btn {
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .news-btn:hover {
            transform: scale(1.05);
        }

        .c-button {
            position: relative;
            display: inline-block;
            padding: 12px 24px;
            color: #198754;
            border: 2px solid #198754;
            background: transparent;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .c-button:hover {
            color: white;
        }

        .c-button__blobs {
            position: absolute;
            inset: 0;
            z-index: -1;
            filter: url(#goo);
        }

        .c-button__blobs div {
            position: absolute;
            width: 120%;
            height: 120%;
            background: #198754;
            border-radius: 50%;
            transform: translateY(100%) scale(1.2);
            transition: transform 0.5s ease;
        }

        .c-button__blobs div:nth-child(1) {
            left: -10%;
            transition-delay: 0s;
        }

        .c-button__blobs div:nth-child(2) {
            left: 30%;
            transition-delay: 0.1s;
        }

        .c-button__blobs div:nth-child(3) {
            left: 70%;
            transition-delay: 0.2s;
        }

        .c-button:hover .c-button__blobs div {
            transform: translateY(0) scale(1.2);
        }
    </style>
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-4">
                <img src="{{ asset('image/logodash.png') }}" alt="" class="img-fluid">
            </div>
            <div class="col-md-4">
                <p class="font fw-bold" style="color: #607c3c;">Halloo Selamat Datang, {{ Auth::user()->name }}!</p>
                <h1>
                    <p class="font fw-bold" style="font-size:40px;">
                        Total Poin Anda
                        <span class=" d-flex align-items-center gold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="width: 30px; height: 30px; margin-left: 10px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-success">{{ Auth::user()->total_poin }}</span>
                        </span>
                    </p>
                </h1>

                <button class="c-button mt-3" onclick="window.location.href='{{ route('penjemputan') }}'">
                    Mulai Sekarang
                    <div class="c-button__blobs">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </button>

                <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute; width:0; height:0;">
                    <defs>
                        <filter id="goo">
                            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  
                        0 1 0 0 0  
                        0 0 1 0 0  
                        0 0 0 18 -7" result="goo" />
                            <feBlend in="SourceGraphic" in2="goo" />
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
            <h2 class="text-center mb-4 text-success fw-bold">
                Berita Terkini
            </h2>

            <div class="row">
                @foreach ($articles as $index => $article)
                    <div class="col-md-3 mb-4">
                        <div class="card news-card h-100 d-flex flex-column">

                            <img src="{{ $article['urlToImage'] ?? asset('image/default-news.jpg') }}"
                                class="card-img-top news-image" 
                                alt="News Image"
                                onerror="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTpTejOwOn7QfCbTDuYRwG0bbe-BaK9ljkKKw&s"
                                >

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title news-title">
                                    {{ $article['title'] }}
                                </h5>

                                <p class="card-text news-text">
                                    {{ Str::limit($article['description'], 100) }}
                                </p>

                                <a href="{{ $article['url'] }}" target="_blank" class="btn btn-success mt-auto news-btn">
                                    Baca Selengkapnya
                                </a>
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