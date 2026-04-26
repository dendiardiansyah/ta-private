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

        .chip-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .chip-warning {
            background: #fff3cd;
            color: #856404;
        }

        .chip-secondary {
            background: #e9ecef;
            color: #495057;
        }
    </style>

    <div class="container my-5">
        <div class="page-shell">
            <div class="mb-4">
                <h2 class="fw-bold text-success mb-1">Riwayat Poin</h2>
                <p class="text-muted mb-0">Pantau histori poin dari transaksi penjemputan Anda.</p>
            </div>

            <div class="card hero-card mb-4">
                <div class="card-body p-4 p-md-5 text-center text-md-start">
                    <p class="mb-2 opacity-75">Total Poin Anda</p>
                    <h1 class="display-4 fw-bold mb-2">{{ number_format(Auth::user()->total_poin, 0, ',', '.') }}</h1>
                    <p class="mb-0 opacity-75">Kumpulkan lebih banyak poin dari penukaran sampah Anda.</p>
                </div>
            </div>

            <div class="card soft-card">
                <div class="card-body p-0">
                    <div class="p-4 pb-2">
                        <h5 class="fw-bold mb-1">Riwayat Transaksi Poin</h5>
                        <p class="text-muted mb-0">Setiap poin terhubung ke transaksi Anda.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Jenis Sampah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Jumlah Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($poinRecords as $index => $poin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $poin->transaksi->jenisSampah->nama_jenis ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($poin->tanggal_diberikan)->format('d-m-Y') }}</td>
                                        <td>
                                            @php
                                                $status = $poin->transaksi->status ?? 'tidak diketahui';
                                            @endphp

                                            @if ($status === 'disetujui' || $status === 'selesai')
                                                <span class="chip chip-success">{{ ucfirst($status) }}</span>
                                            @elseif ($status === 'pending' || $status === 'diproses')
                                                <span class="chip chip-warning">{{ ucfirst($status) }}</span>
                                            @else
                                                <span class="chip chip-secondary">{{ ucfirst($status) }}</span>
                                            @endif
                                        </td>
                                        <td class="text-success fw-bold">
                                            +{{ number_format($poin->jumlah_poin, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat poin.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>