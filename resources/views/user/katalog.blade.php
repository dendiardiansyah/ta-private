<x-app-layout>
    <style>
        .page-shell {
            background: linear-gradient(180deg, #f6fff9 0%, #ffffff 100%);
            border-radius: 24px;
            padding: 1.5rem;
        }

        .soft-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(16, 24, 40, .08);
            overflow: hidden;
        }

        .katalog-image-wrap {
            height: 220px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .katalog-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 1rem;
        }

        .price-chip {
            display: inline-block;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 999px;
            font-weight: 600;
            padding: .35rem .8rem;
            font-size: .85rem;
        }
    </style>

    <div class="container my-5">
        <div class="page-shell">
            <div class="mb-4">
                <h2 class="fw-bold text-success mb-1">Katalog Sampah</h2>
                <p class="text-muted mb-0">Daftar kategori sampah beserta harga per kilogram.</p>
            </div>

            <div class="row g-4">
                @forelse ($jenisSampahs as $jenisSampah)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card soft-card h-100">
                            <div class="katalog-image-wrap">
                                <img src="{{ $jenisSampah->gambar ? asset('storage/' . $jenisSampah->gambar) : asset('image/default.png') }}"
                                    class="katalog-image" alt="{{ $jenisSampah->nama_jenis }}"
                                    onerror="this.onerror=null;this.src='{{ asset('image/default.png') }}'">
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-success fw-bold mb-2">{{ $jenisSampah->nama_jenis }}</h5>
                                <p class="card-text text-muted mb-3">{{ $jenisSampah->deskripsi }}</p>

                                <div class="mt-auto">
                                    <span
                                        class="price-chip">Rp{{ number_format($jenisSampah->harga_sampah, 0, ',', '.') }}/kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border text-center mb-0">
                            Data katalog belum tersedia.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>