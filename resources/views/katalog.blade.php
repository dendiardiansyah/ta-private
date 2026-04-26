<x-app-layout>
    <div class="container my-5">
        <h1 class="text-center text-success mb-5 fw-bold" style="font-size: 40px;">
            Katalog Sampah
        </h1>

        <div class="row">
            @foreach ($jenisSampahs as $jenisSampah)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-120">

                            <img src="{{ $jenisSampah->gambar
                ? asset('storage/' . $jenisSampah->gambar)
                : asset('image/default.png') }}" class="card-img-top img-fluid"
                                style="height: 240px; object-fit: contain;" alt="{{ $jenisSampah->nama_jenis }}"
                                onerror="this.onerror=null;this.src='{{ asset('image/default.png') }}'">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-success">
                                    {{ $jenisSampah->nama_jenis }}
                                </h5>

                                <p class="card-text">
                                    {{ $jenisSampah->deskripsi }}
                                </p>

                                <p class="text-muted mt-auto">
                                    Harga: Rp{{ number_format($jenisSampah->harga_sampah, 0, ',', '.') }}/kg
                                </p>
                            </div>

                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</x-app-layout>