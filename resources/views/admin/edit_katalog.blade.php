<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Sertakan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('image/logomain.png') }}" alt="Logo">Dashboard
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h3>Edit Jenis Sampah</h3>

        <!-- Form Edit Jenis Sampah -->
        <div class="card">
            <div class="card-header">
                <h4>Form Edit Jenis Sampah</h4>
            </div>
            <div class="card-body">
                <form id="update-form" action="{{ route('admin.katalog.update', $jenisSampah->jenis_sampah_id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Jenis Sampah</label>
                        <input type="text" class="form-control" id="nama" name="nama_jenis"
                            value="{{ old('nama', $jenisSampah->nama_jenis) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi"
                            value="{{ old('deskripsi', $jenisSampah->deskripsi) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga_sampah"
                            value="{{ old('harga', $jenisSampah->harga_sampah) }}" required>
                    </div>

                    <div class="form-group mb-5">
                        <label for="gambar">Gambar</label>
                        <input type="file" class="form-control mb-3" id="gambar" name="gambar">
                        @if($jenisSampah->gambar)
                            <span>{{ $jenisSampah->gambar }}</span>
                        @endif
                    </div>

                    <!-- Tombol Update -->
                    <button type="button" class="btn btn-success" onclick="confirmUpdate()">Update</button>
                    <a href="{{ route('admin.katalog') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Logic -->
    <script>
        function confirmUpdate() {
            Swal.fire({
                title: 'Apakah Anda yakin ingin memperbarui data ini?',
                text: "Periksa kembali data sebelum mengirim perubahan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Perbarui',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('update-form').submit();
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>