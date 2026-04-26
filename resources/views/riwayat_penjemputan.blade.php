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
        }

        .table thead th {
            background: #198754;
            color: #fff;
            border-bottom: 0;
            white-space: nowrap;
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
        <div class="page-shell">
            <div class="mb-4">
                <h2 class="fw-bold text-success mb-1">Riwayat Penjemputan</h2>
                <p class="text-muted mb-0">Pantau status penjemputan sampah Anda dan poin yang didapatkan.</p>
            </div>

            <div class="card soft-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Alamat</th>
                                    <th>Jumlah (kg)</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Poin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->user->name }}</td>
                                        <td>{{ $transaksi->jenisSampah->nama_jenis }}</td>
                                        <td>{{ $transaksi->alamat_penjemputan }}</td>
                                        <td>{{ $transaksi->jumlah }}</td>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($transaksi->status === 'pending')
                                                <span class="chip chip-warning">Pending</span>
                                            @elseif ($transaksi->status === 'disetujui' || $transaksi->status === 'selesai')
                                                <span class="chip chip-success">{{ ucfirst($transaksi->status) }}</span>
                                            @else
                                                <span class="chip chip-secondary">{{ ucfirst($transaksi->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $totalPoin = $transaksi->poin->sum('jumlah_poin');
                                            @endphp
                                            <span
                                                class="fw-semibold text-success">{{ $totalPoin > 0 ? number_format($totalPoin, 0, ',', '.') . ' poin' : '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('penjemputan.edit', $transaksi->transaksi_id) }}"
                                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                                <form action="{{ route('penjemputan.destroy', $transaksi->transaksi_id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Tidak ada riwayat penjemputan
                                            yang tersedia.</td>
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