<x-app-layout>
    <style>
        .withdraw-page {
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

        .table thead th {
            background: #198754;
            color: #fff;
            border-bottom: 0;
        }

        .chip {
            border-radius: 999px;
            padding: .3rem .8rem;
            font-size: .8rem;
            font-weight: 600;
            display: inline-block;
        }

        .chip-warning {
            background: #fff3cd;
            color: #856404;
        }

        .chip-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .chip-secondary {
            background: #e9ecef;
            color: #495057;
        }
    </style>

    <div class="container my-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="withdraw-page">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold text-success mb-1">Penarikan Poin</h2>
                    <p class="text-muted mb-0">Kelola penukaran poin Anda dengan tampilan yang lebih ringkas.</p>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="card hero-card h-100">
                        <div class="card-body p-4 p-md-5">
                            <p class="mb-2 opacity-75">Total Poin Anda</p>
                            <h1 class="display-4 fw-bold mb-2">
                                {{ number_format(Auth::user()->total_poin, 0, ',', '.') }}</h1>
                            <p class="mb-0 opacity-75">Semakin banyak transaksi, semakin besar poin yang bisa ditarik.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card soft-card h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Ajukan Penarikan</h5>
                            <form action="{{ route('penarikan.store') }}" method="POST" class="d-grid gap-3">
                                @csrf
                                <div>
                                    <label for="jumlahPoin" class="form-label fw-semibold">Jumlah Poin</label>
                                    <input type="number" name="jumlah_poin" id="jumlahPoin" class="form-control"
                                        placeholder="Contoh: 500" min="100" value="{{ old('jumlah_poin') }}" required>
                                    @error('jumlah_poin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-success fw-semibold">
                                    Ajukan Penarikan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card soft-card">
                <div class="card-body p-0">
                    <div class="p-4 pb-2">
                        <h5 class="fw-bold mb-1">Riwayat Penarikan Poin</h5>
                        <p class="text-muted mb-0">Daftar pengajuan penarikan poin Anda.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penarikanPoin as $index => $penarikan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ optional($penarikan->created_at)->format('d-m-Y H:i') }}</td>
                                        <td class="fw-bold text-success">
                                            {{ number_format($penarikan->jumlah_poin, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($penarikan->jumlah_uang, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($penarikan->status_penarikan === 'diproses')
                                                <span class="chip chip-warning">Diproses</span>
                                            @elseif ($penarikan->status_penarikan === 'selesai' || $penarikan->status_penarikan === 'berhasil')
                                                <span
                                                    class="chip chip-success">{{ ucfirst($penarikan->status_penarikan) }}</span>
                                            @else
                                                <span
                                                    class="chip chip-secondary">{{ ucfirst($penarikan->status_penarikan) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat penarikan
                                            poin.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: @json(session('error')),
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>
    @endif


</x-app-layout>