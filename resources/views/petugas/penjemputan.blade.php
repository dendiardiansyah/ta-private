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

        .table tbody td {
            vertical-align: top;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .table tbody tr:hover {
            background: #f8fff9;
        }

        .section-card {
            border: 1px solid #e9ecef;
            border-radius: 16px;
            background: #fff;
            padding: 1rem;
        }

        .meta-label {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6c757d;
            margin-bottom: .15rem;
        }

        .meta-value {
            color: #212529;
            font-weight: 600;
            line-height: 1.4;
        }

        .sampah-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .detail-row {
            background: #f8f9fa;
            border-left: 3px solid #198754;
        }

        .sampah-item-row {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
        }

        .btn-remove-item {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="container my-5">
        <div class="page-shell">
            <div class="mb-4">
                <h2 class="fw-bold text-success mb-1">Daftar Penjemputan</h2>
                <p class="text-muted mb-0">Kelola daftar penjemputan sampah yang ditugaskan kepada Anda.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card soft-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nasabah</th>
                                    <th>Alamat</th>
                                    <th style="min-width: 280px;">Detail Sampah</th>
                                    <th style="width: 140px;">Status</th>
                                    <th style="width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $transaksi)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $transaksi->user->name }}</div>
                                            <small class="text-muted">ID #{{ $transaksi->transaksi_id }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block">{{ Str::limit($transaksi->alamat_penjemputan, 60) }}</small>
                                        </td>
                                        <td>
                                            @if($transaksi->details->isNotEmpty())
                                                <div class="d-flex flex-column gap-2">
                                                    @foreach($transaksi->details as $detail)
                                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                                            <span class="badge bg-success rounded-pill px-3 py-2">{{ $detail->jenisSampah->nama_jenis }}</span>
                                                            <span class="text-muted small">{{ number_format($detail->berat, 2) }} kg</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="mt-2">
                                                    <span class="badge bg-light text-success border border-success-subtle">
                                                        Total: {{ number_format($transaksi->details->sum('berat'), 2) }} kg
                                                    </span>
                                                </div>
                                            @else
                                                <small class="text-muted fst-italic">Belum diinput</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $transaksi->status === 'Selesai' ? 'success' : ($transaksi->status === 'Menunggu Petugas' ? 'warning text-dark' : 'info') }}">
                                                {{ $transaksi->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaksi->status !== 'Selesai')
                                                <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#updateModal{{ $transaksi->transaksi_id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                    </svg>
                                                    Update
                                                </button>
                                            @else
                                                <span class="text-success"><small><strong>✓ Selesai</strong></small></span>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal Update -->
                                    <div class="modal fade" id="updateModal{{ $transaksi->transaksi_id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('petugas.update', $transaksi->transaksi_id) }}" method="POST" id="updateForm{{ $transaksi->transaksi_id }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <div>
                                                            <h5 class="modal-title mb-1">Update Penjemputan</h5>
                                                            <small class="text-muted">Transaksi #{{ $transaksi->transaksi_id }}</small>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="section-card mb-3">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <div class="meta-label">Nasabah</div>
                                                                    <div class="meta-value">{{ $transaksi->user->name }}</div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="meta-label">Tanggal</div>
                                                                    <div class="meta-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="meta-label">Alamat</div>
                                                                    <div class="meta-value">{{ $transaksi->alamat_penjemputan }}</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="section-card mb-3">
                                                            <div class="sampah-header mb-2">
                                                                <div>
                                                                    <h6 class="mb-1 fw-semibold">Status Penjemputan</h6>
                                                                    <small class="text-muted">Perbarui progres penjemputan sesuai kondisi lapangan.</small>
                                                                </div>
                                                            </div>
                                                            <select name="status" class="form-select mt-3" required>
                                                                <option value="Menunggu Petugas" {{ $transaksi->status == 'Menunggu Petugas' ? 'selected' : '' }}>Menunggu Petugas</option>
                                                                <option value="Menuju Lokasi" {{ $transaksi->status == 'Menuju Lokasi' ? 'selected' : '' }}>Menuju Lokasi</option>
                                                                <option value="Sedang Diangkut" {{ $transaksi->status == 'Sedang Diangkut' ? 'selected' : '' }}>Sedang Diangkut</option>
                                                                <option value="Selesai" {{ $transaksi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                            </select>
                                                        </div>

                                                        <div class="section-card">
                                                            <div class="sampah-header mb-2">
                                                                <div>
                                                                    <h6 class="mb-1 fw-semibold">Jenis Sampah & Berat</h6>
                                                                    <small class="text-muted">Tambahkan satu atau lebih item sampah yang berhasil diambil.</small>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" onclick="addSampahRow{{ $transaksi->transaksi_id }}()">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                                                    </svg>
                                                                    Tambah Item
                                                                </button>
                                                            </div>
                                                            <small class="text-muted d-block mb-3">Berat dapat berupa desimal, misalnya 0.50 kg atau 1.25 kg.</small>

                                                            <div id="sampahContainer{{ $transaksi->transaksi_id }}">
                                                                @if($transaksi->details->isNotEmpty())
                                                                    @foreach($transaksi->details as $index => $detail)
                                                                        <div class="sampah-item-row">
                                                                            <div class="row g-3 align-items-end">
                                                                                <div class="col-md-7">
                                                                                    <label class="form-label mb-1 small text-muted">Jenis Sampah</label>
                                                                                    <select name="details[{{ $index }}][jenis_sampah_id]" class="form-select">
                                                                                        <option value="">Pilih Jenis Sampah</option>
                                                                                        @foreach($jenisSampahList as $js)
                                                                                            <option value="{{ $js->jenis_sampah_id }}" {{ $detail->jenis_sampah_id == $js->jenis_sampah_id ? 'selected' : '' }}>
                                                                                                {{ $js->nama_jenis }} (Rp {{ number_format($js->harga_sampah) }}/kg)
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <label class="form-label mb-1 small text-muted">Berat (kg)</label>
                                                                                    <input type="number" name="details[{{ $index }}][berat]" class="form-control"
                                                                                        placeholder="0.00" step="0.01" min="0.01" value="{{ $detail->berat }}">
                                                                                </div>
                                                                                <div class="col-md-1 text-end">
                                                                                    <button type="button" class="btn btn-danger btn-remove-item" title="Hapus item" onclick="this.closest('.sampah-item-row').remove()">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 1 1 0V6z"/>
                                                                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="sampah-item-row">
                                                                        <div class="row g-3 align-items-end">
                                                                            <div class="col-md-7">
                                                                                <label class="form-label mb-1 small text-muted">Jenis Sampah</label>
                                                                                <select name="details[0][jenis_sampah_id]" class="form-select">
                                                                                    <option value="">Pilih Jenis Sampah</option>
                                                                                    @foreach($jenisSampahList as $js)
                                                                                        <option value="{{ $js->jenis_sampah_id }}">
                                                                                            {{ $js->nama_jenis }} (Rp {{ number_format($js->harga_sampah) }}/kg)
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="form-label mb-1 small text-muted">Berat (kg)</label>
                                                                                <input type="number" name="details[0][berat]" class="form-control"
                                                                                    placeholder="0.00" step="0.01" min="0.01">
                                                                            </div>
                                                                            <div class="col-md-1 text-end">
                                                                                <button type="button" class="btn btn-danger btn-remove-item" title="Hapus item" onclick="this.closest('.sampah-item-row').remove()">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 1 1 0V6z"/>
                                                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="alert alert-info mt-3 mb-0">
                                                                <small><strong>Info:</strong> Klik “Tambah Item” untuk menambah baris sampah lain.</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        let sampahRowIndex{{ $transaksi->transaksi_id }} = {{ $transaksi->details->count() > 0 ? $transaksi->details->count() : 1 }};
                                        
                                        function addSampahRow{{ $transaksi->transaksi_id }}() {
                                            const container = document.getElementById('sampahContainer{{ $transaksi->transaksi_id }}');
                                            const newRow = `
                                                <div class="sampah-item-row">
                                                    <div class="row g-3 align-items-end">
                                                        <div class="col-md-7">
                                                            <label class="form-label mb-1 small text-muted">Jenis Sampah</label>
                                                            <select name="details[${sampahRowIndex{{ $transaksi->transaksi_id }}}][jenis_sampah_id]" class="form-select">
                                                                <option value="">Pilih Jenis Sampah</option>
                                                                @foreach($jenisSampahList as $js)
                                                                    <option value="{{ $js->jenis_sampah_id }}">
                                                                        {{ $js->nama_jenis }} (Rp {{ number_format($js->harga_sampah) }}/kg)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label mb-1 small text-muted">Berat (kg)</label>
                                                            <input type="number" name="details[${sampahRowIndex{{ $transaksi->transaksi_id }}}][berat]" class="form-control"
                                                                placeholder="0.00" step="0.01" min="0.01">
                                                        </div>
                                                        <div class="col-md-1 text-end">
                                                            <button type="button" class="btn btn-danger btn-remove-item" title="Hapus item" onclick="this.closest('.sampah-item-row').remove()">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
                                            container.insertAdjacentHTML('beforeend', newRow);
                                            sampahRowIndex{{ $transaksi->transaksi_id }}++;
                                        }
                                    </script>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="text-muted mb-2" viewBox="0 0 16 16">
                                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                                            </svg>
                                            <p class="text-muted mb-0">Belum ada penugasan penjemputan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($transaksis->hasPages())
                <div class="mt-3">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
