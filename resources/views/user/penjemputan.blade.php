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
                            <h2 class="fw-bold mb-2">Ajukan Transaksi Sampah</h2>
                            <p class="mb-0 opacity-75">Isi data penjemputan dengan benar agar proses lebih cepat.</p>
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
                                    <label for="jenis_sampah_id" class="form-label fw-semibold">Jenis Sampah</label>
                                    <select name="jenis_sampah_id" id="jenis_sampah_id" class="form-select" required>
                                        <option value="">Pilih Jenis Sampah</option>
                                        @foreach ($jenisSampah as $jenis)
                                            <option value="{{ $jenis->jenis_sampah_id }}"
                                                @selected(old('jenis_sampah_id') == $jenis->jenis_sampah_id)>
                                                {{ $jenis->nama_jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_sampah_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div>
                                    <label for="alamat_penjemputan" class="form-label fw-semibold">Alamat
                                        Penjemputan</label>
                                    <input type="text" name="alamat_penjemputan" id="alamat_penjemputan"
                                        class="form-control" value="{{ old('alamat_penjemputan') }}" required>
                                    @error('alamat_penjemputan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="jumlah" class="form-label fw-semibold">Jumlah (kg)</label>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1"
                                            value="{{ old('jumlah') }}" required>
                                        @error('jumlah')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="tanggal_transaksi" class="form-label fw-semibold">Tanggal
                                            Transaksi</label>
                                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                            class="form-control" value="{{ old('tanggal_transaksi') }}" required>
                                        @error('tanggal_transaksi')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
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