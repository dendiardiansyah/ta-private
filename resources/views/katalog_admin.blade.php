<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Katalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .navbar-brand img {
        width: 30px;
        /* Adjust the size of the logo */
        height: auto;
        margin-right: 10px;
        /* Spacing between logo and text */
    }
</style>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('image/logomain.png') }}" alt="Logo">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.transaksi') }}">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.katalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">


        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">Katalog Sampah</h1>
            <!-- Button Tambah -->
            <a href="{{ route('admin.katalog.create') }}" class="btn btn-outline-success">+ Tambah Jenis Sampah</a>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Jenis Sampah yang Tersedia</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Sampah</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenisSampahs as $jenisSampah)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jenisSampah->nama_jenis }}</td>
                                <td>{{ $jenisSampah->deskripsi }}</td>
                                <td>{{ $jenisSampah->harga_sampah }}</td>
                                <td>
                                    <!-- Menampilkan Nama Gambar sebagai teks -->
                                    @if($jenisSampah->gambar)
                                        <span>{{ $jenisSampah->gambar }}</span>
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button-container" style="display: flex; gap: 5px;">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('admin.katalog.edit', $jenisSampah->jenis_sampah_id) }}"
                                            class="btn btn-warning btn-sm " style="width: 60px;">Edit</a>

                                        <!-- Tombol Delete -->
                                        <form action="{{ route('admin.katalog.delete', $jenisSampah->jenis_sampah_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-custom">Delete</button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>