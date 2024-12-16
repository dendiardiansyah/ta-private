<x-app-layout>
    <div class="container my-5">
        <h1 class="text-center mb-4 text-success fw-bold" style="font-size:50px" ;>Riwayat Poin Nasabah</h1>

        <!-- Total Poin -->
        <div class="card shadow-lg border-0 mb-4" style="background: linear-gradient(45deg, #6ab04c, #badc58); color: white;">
            <div class="card-body text-center">
                <h3 class="mb-3">Total Poin Anda</h3>
                <h1 class="fw-bold" style="font-size: 4rem;">{{ Auth::user()->total_poin }}</h1>
                <p class="mb-0">Kumpulkan poin lebih banyak dari hasil penukaran sampah Anda!</p>
            </div>
        </div>

        <!-- Tabel Riwayat Poin -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Riwayat Transaksi Poin</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-success">
                            <tr>
                                <th>#</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jumlah Poin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($poinRecords as $index => $poin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $poin->transaksi->jenisSampah->nama_jenis ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($poin->tanggal_diberikan)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ $poin->transaksi ? $poin->transaksi->status : 'Tidak Diketahui' }}
                                    </span>
                                </td>
                                <td class="text-success fw-bold">+{{ $poin->jumlah_poin }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailPoinModal{{ $index }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal untuk Detail Poin -->
                            <div class="modal fade" id="detailPoinModal{{ $index }}" tabindex="-1" aria-labelledby="detailPoinModalLabel{{ $index }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="detailPoinModalLabel{{ $index }}">Detail Poin</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Deskripsi:</strong> {{ $poin->transaksi->jenisSampah->nama_jenis ?? 'N/A' }}</p>
                                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($poin->tanggal_diberikan)->format('d-m-Y') }}</p>
                                            <p><strong>Status:</strong> {{ $poin->transaksi->status ?? 'Tidak Diketahui' }}</p>
                                            <p><strong>Jumlah Poin:</strong> +{{ $poin->jumlah_poin }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

</x-app-layout>