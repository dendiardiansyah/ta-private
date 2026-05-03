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
    </style>

    <div class="container my-5">
        <div class="page-shell">
            <div class="mb-4">
                <h2 class="fw-bold text-success mb-1">Daftar Penjemputan</h2>
                <p class="text-muted mb-0">Kelola daftar penjemputan sampah yang ditugaskan kepada Anda.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card soft-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nasabah</th>
                                    <th>Alamat</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr>
                                        <td>#{{ $transaksi->transaksi_id }}</td>
                                        <td>{{ $transaksi->user->name }}</td>
                                        <td>{{ $transaksi->alamat_penjemputan }}</td>
                                        <td>{{ $transaksi->jenisSampah->nama_jenis }}</td>
                                        <td>{{ $transaksi->jumlah }} kg</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $transaksi->status === 'Selesai' ? 'success' : ($transaksi->status === 'Menunggu Petugas' ? 'warning text-dark' : 'info') }}">
                                                {{ $transaksi->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaksi->status !== 'Selesai')
                                                <form action="{{ route('petugas.update', $transaksi->transaksi_id) }}"
                                                    method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select form-select-sm"
                                                        style="width: auto;">
                                                        <option value="Menunggu Petugas" {{ $transaksi->status == 'Menunggu Petugas' ? 'selected' : '' }}>Menunggu Petugas</option>
                                                        <option value="Menuju Lokasi" {{ $transaksi->status == 'Menuju Lokasi' ? 'selected' : '' }}>Menuju Lokasi</option>
                                                        <option value="Sedang Diangkut" {{ $transaksi->status == 'Sedang Diangkut' ? 'selected' : '' }}>Sedang Diangkut</option>
                                                        <option value="Selesai" {{ $transaksi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                </form>
                                            @else
                                                <span class="text-muted"><small><i>Selesai</i></small></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Belum ada penugasan penjemputan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>