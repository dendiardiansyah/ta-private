<x-app-layout>
    <style>
        .page-shell {
            background: linear-gradient(180deg, #f6fff9 0%, #ffffff 100%);
            border-radius: 24px;
            padding: 1.5rem;
        }

        .hero-card {
            border: 0;
            border-radius: 20px;
            background: linear-gradient(135deg, #1f9d55 0%, #6ccf8a 100%);
            color: #fff;
            box-shadow: 0 14px 36px rgba(25, 135, 84, .25);
        }

        .soft-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(16, 24, 40, .08);
        }
    </style>

    <div class="container my-5">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="page-shell">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="card hero-card h-100">
                        <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                            <p class="mb-2 opacity-75">Form Penjemputan</p>
                            <h2 class="fw-bold mb-2">Ajukan Permintaan Penjemputan</h2>
                            <p class="mb-0 opacity-75">Tentukan tanggal penjemputan. Petugas akan mencatat jenis dan berat sampah saat pengambilan.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card soft-card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Detail Penjemputan</h5>

                            <form action="{{ route('transaksi.store') }}" method="POST" class="d-grid gap-3">
                                @csrf

                                <div>
                                    <label for="alamat_penjemputan" class="form-label fw-semibold">Alamat
                                        Penjemputan</label>
                                    <textarea name="alamat_penjemputan" id="alamat_penjemputan" rows="3"
                                        class="form-control bg-light" disabled readonly>{{ Auth::user()->alamat }}</textarea>
                                    <small class="text-muted">Alamat diambil dari profil Anda. Ubah di halaman profil jika perlu.</small>
                                </div>

                                <div>
                                    <label for="tanggal_transaksi" class="form-label fw-semibold">Tanggal
                                        Penjemputan</label>
                                    <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                        class="form-control" value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" required>
                                    @error('tanggal_transaksi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">Pilih tanggal yang Anda inginkan untuk penjemputan sampah.</small>
                                </div>

                                <div class="alert alert-info mb-0">
                                    <div class="d-flex align-items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle flex-shrink-0 mt-1" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                        </svg>
                                        <small><strong>Catatan:</strong> Jenis sampah dan beratnya akan dicatat oleh petugas saat pengambilan.</small>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success fw-semibold mt-2">Ajukan
                                    Penjemputan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>