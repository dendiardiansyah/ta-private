<x-app-layout>
    <style>
        
        .svg-wrapper-1 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .svg-wrapper {
            animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        button:hover .svg-wrapper {
            animation: fly-1 0.6s ease-in-out infinite alternate;
        }

        button:hover svg {
            transform: translateX(1.2em) rotate(45deg) scale(1.1);
        }

        button:hover span {
            transform: translateX(5em);
        }

        button:active {
            transform: scale(0.95);
        }

        @keyframes fly-1 {
            from {
                transform: translateY(0.1em);
            }

            to {
                transform: translateY(-0.1em);
            }
        }
    </style>
    <div class="container my-5">
        <!-- Judul Halaman -->
        <h1 class="text-center text-success fw-bold mb-4">Riwayat Penarikan Poin</h1>

        <!-- Alert Sukses atau Error -->
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

        <!-- Total Poin -->
        <div class="card shadow-lg border-0 mb-4" style="background: linear-gradient(135deg, #6ab04c, #b8e994); color: white;">
            <div class="card-body text-center">
                <h3>Total Poin Anda</h3>
                <h1 class="fw-bold display-4">{{ Auth::user()->total_poin }}</h1>
                <p class="mb-0">Kumpulkan lebih banyak poin dari penukaran sampah Anda!</p>
            </div>
        </div>

        <!-- Form Penarikan Poin -->
        <div class="card shadow-sm border-0 mb-4 mx-auto" style="max-width: 400px;">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">Ajukan Penarikan Poin</h4>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('penarikan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="jumlahPoin" class="form-label fw-semibold">Jumlah Poin</label>
                        <input type="number" name="jumlah_poin" id="jumlahPoin" class="form-control rounded-pill" placeholder="Masukkan jumlah poin" required>
                        @error('jumlah_poin')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn fw-bold" style="font-family: inherit; font-size: 16px; background: #28a745; color: white; padding: 0.5em 1em; display: flex; align-items: center; border: none; border-radius: 16px; overflow: hidden; transition: all 0.2s; cursor: pointer; max-width: 200px; margin: 0 auto;">
                        <div class="svg-wrapper-1">
                            <div class="svg-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path fill="currentColor" d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"></path>
                                </svg>
                            </div>
                        </div>
                        <span>Ajukan</span>
                    </button>


                </form>
            </div>
        </div>

        <!-- Tabel Riwayat Penarikan -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">Riwayat Penarikan Poin</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="bg-success text-white">
                            <tr>
                                <th>#</th>
                                <th>Tanggal Penarikan</th>
                                <th>Jumlah Poin</th>
                                <th>Jumlah Uang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penarikanPoin as $index => $penarikan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($penarikan->created_at)->format('d-m-Y H:i') }}</td>
                                <td class="text-success fw-bold">{{ $penarikan->jumlah_poin }}</td>
                                <td>Rp {{ number_format($penarikan->jumlah_uang, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $penarikan->status_penarikan === 'diproses' ? 'warning' : ($penarikan->status_penarikan === 'berhasil' ? 'success' : 'danger') }}">
                                        {{ ucfirst($penarikan->status_penarikan) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat penarikan poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('
                success ') }}',
                confirmButtonColor: '#28a745',
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('
                error ') }}',
                confirmButtonColor: '#dc3545',
                timer: 3000,
                timerProgressBar: true,
            });
        });
    </script>
    @endif


</x-app-layout>