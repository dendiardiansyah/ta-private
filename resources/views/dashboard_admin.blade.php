<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Pelaku Usaha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar-brand img {
        width: 30px;
        height: auto;
        margin-right: 10px;
    }
</style>

<body>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Welcome Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Selamat Datang di Dashboard Admin Pelaku Usaha</h3>
                    </div>
                    <div class="card-body">
                        <p>Halo, {{ auth('pelaku_usaha')->user()->nama }}! Anda berhasil login sebagai Admin Pelaku Usaha.</p>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>

                <!-- Data Penjemputan Table -->
                <div class="card">
                    <div class="card-header">
                        <h4>Data Penjemputan yang Menunggu Persetujuan</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Alamat Penjemputan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksis as $transaksi)
                                <tr>
                                    <td>{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $loop->iteration }}</td>
                                    <td>{{ $transaksi->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                                    <td>{{ $transaksi->alamat_penjemputan }}</td>
                                    <td>{{ $transaksi->status }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data tersedia.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $transaksis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('pelaku_usaha.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: 'Data Penjemputan',
                    data: [12, 19, 3, 5, 2, 3.8],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
