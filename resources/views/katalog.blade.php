<x-app-layout>
    <div class="container my-5">
        <h1 class="text-center mb-4 text-success mb-5" style="font-size: 40px; font-weight:bold;">Katalog Sampah</h1>
        <div class="row">
            @foreach ($jenisSampahs as $jenisSampah)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <!-- Menampilkan gambar jika ada -->
                    @if ($jenisSampah->gambar)
                    <img src="{{ asset('storage/' . $jenisSampah->gambar) }}" class="card-img-top img-fluid" alt="{{ $jenisSampah->nama }}" style="height: 200px; object-fit: cover;">
                    @else
                    <img src="{{ asset('image/default.png') }}" class="card-img-top img-fluid" alt="Default Image" style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title text-success">{{ $jenisSampah->nama_jenis }}</h5>
                        <p class="card-text">{{ $jenisSampah->deskripsi }}</p>
                        <p class="text-muted">Harga per Kilo: Rp{{ number_format($jenisSampah->harga_sampah, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>