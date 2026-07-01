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
                                    <th>Tanggal</th>
                                    <th>Jenis Sampah & Berat</th>
                                    <th>Total Berat</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                    <th>Poin Diperoleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($transaksi->details->isNotEmpty())
                                                <div class="d-flex flex-column gap-1">
                                                    @foreach($transaksi->details as $detail)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-success">{{ $detail->jenisSampah->nama_jenis }}</span>
                                                            <small class="text-muted">{{ number_format($detail->berat, 2) }} kg</small>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <small class="text-muted fst-italic">Belum diinput petugas</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transaksi->details->isNotEmpty())
                                                <strong class="text-success">{{ number_format($transaksi->details->sum('berat'), 2) }} kg</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaksi->status === 'Menunggu Petugas')
                                                <span class="chip chip-warning">{{ $transaksi->status }}</span>
                                            @elseif ($transaksi->status === 'Selesai')
                                                <span class="chip chip-success">{{ $transaksi->status }}</span>
                                            @else
                                                <span class="chip chip-secondary"
                                                    style="background:#cff4fc;color:#055160;">{{ $transaksi->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="fw-semibold text-primary">{{ $transaksi->petugas ? $transaksi->petugas->name : 'Belum Ditugaskan' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $totalPoin = $transaksi->poin->sum('jumlah_poin');
                                            @endphp
                                            @if($totalPoin > 0)
                                                <div class="d-flex align-items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                    </svg>
                                                    <span class="fw-bold text-success">{{ number_format($totalPoin, 0, ',', '.') }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                @if ($transaksi->status === 'Menunggu Petugas' || $transaksi->status === 'pending')
                                                    <a href="{{ route('penjemputan.edit', $transaksi->transaksi_id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('penjemputan.destroy', $transaksi->transaksi_id) }}"
                                                        method="POST" onsubmit="return confirm('Hapus permintaan penjemputan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-muted mb-3 opacity-50" viewBox="0 0 16 16">
                                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                                            </svg>
                                            <p class="mb-0">Belum ada riwayat penjemputan.</p>
                                            <small class="text-muted">Ajukan penjemputan baru untuk memulai.</small>
                                        </td>
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