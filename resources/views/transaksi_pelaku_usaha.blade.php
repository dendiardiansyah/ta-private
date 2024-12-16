<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Pelaku Usaha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    .navbar-brand img {
        width: 30px;
        /* Adjust the size of the logo */
        height: auto;
        margin-right: 10px;
        /* Spacing between logo and text */
    }

    .element {
        background-image: url('/image/black.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>

<body class="element">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('pelaku_usaha.dashboard') }}">
                <img src="{{ asset('image/logomain.png') }}" alt="Logo">Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pelaku-usaha/dashboard') ? 'active' : '' }}"
                            href="{{ route('pelaku_usaha.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pelaku-usaha/transaksi') ? 'active' : '' }}"
                            href="{{ route('pelaku_usaha.transaksi') }}">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pelaku-usaha/katalog') ? 'active' : '' }}"
                            href="{{ route('pelaku_usaha.katalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pelaku_usaha.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-3 text-center">Daftar Transaksi</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksis as $transaksi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaksi->user->name ?? 'Tidak Ditemukan' }}</td>
                                    <td>{{ $transaksi->jenisSampah->nama_jenis ?? 'Tidak Ditemukan' }}</td>
                                    <td>{{ $transaksi->jumlah }} kg</td>
                                    <td>
                                        <!-- Form untuk mengupdate status dan poin -->
                                        <form action="{{ route('pelaku_usaha.transaksi.update', $transaksi->transaksi_id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group mb-2">
                                                <!-- Dropdown Status -->
                                                <select name="status" class="form-control status-dropdown" data-id="{{ $transaksi->transaksi_id }}">
                                                    <option value="pending" {{ $transaksi->status == 'pending' ? 'selected' : '' }}>pending</option>
                                                    <option value="disetujui" {{ $transaksi->status == 'disetujui' ? 'selected' : '' }}>disetujui</option>
                                                    <option value="ditolak" {{ $transaksi->status == 'ditolak' ? 'selected' : '' }}>ditolak</option>
                                                </select>

                                                <!-- Input Poin -->
                                                <input type="number" name="poin" class="form-control poin-input" placeholder="Jumlah Poin"
                                                    id="poin-{{ $transaksi->transaksi_id }}"
                                                    {{ $transaksi->status != 'disetujui' ? 'disabled' : '' }} />

                                                <!-- Tombol Submit -->
                                                <button type="submit" class="btn btn-outline-success">Update</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Script for Status Update -->
    @if(session('status'))
    <script>
        Swal.fire({
            title: 'Status penjemputan Berhasil di ubah',
            text: '{{ session('
            status ') }}',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    </script>
    @endif

    <script>
        // Mengaktifkan atau menonaktifkan input poin berdasarkan pilihan status
        document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
            const transaksiId = dropdown.dataset.id;
            const poinInput = document.getElementById(`poin-${transaksiId}`);

            dropdown.addEventListener('change', function() {
                if (this.value === 'disetujui') {
                    poinInput.removeAttribute('disabled'); // Aktifkan input poin
                } else {
                    poinInput.setAttribute('disabled', 'disabled'); // Nonaktifkan input poin
                    poinInput.value = ''; // Kosongkan input poin
                }
            });

            // Set kondisi awal berdasarkan status yang sudah ada
            if (dropdown.value === 'disetujui') {
                poinInput.removeAttribute('disabled');
            } else {
                poinInput.setAttribute('disabled', 'disabled');
                poinInput.value = ''; // Kosongkan input poin jika tidak disetujui
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>