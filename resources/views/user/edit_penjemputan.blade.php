<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-xl font-bold mb-6">Edit Penjemputan</h1>

        <!-- Form Edit Penjemputan -->
        <form action="{{ route('penjemputan.update', $transaksi->transaksi_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="jenis_sampah_id" class="block text-sm font-medium text-gray-700">Jenis Sampah</label>
                <select name="jenis_sampah_id" id="jenis_sampah_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="">Pilih Jenis Sampah</option>
                    @foreach($jenisSampah as $jenis)
                    <option value="{{ $jenis->jenis_sampah_id }}"
                        {{ $jenis->jenis_sampah_id == $transaksi->jenis_sampah_id ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="alamat_penjemputan" class="block text-sm font-medium text-gray-700">Alamat Penjemputan</label>
                <input type="text" name="alamat_penjemputan" id="alamat_penjemputan" class="mt-1 block w-full border-gray-300 rounded-md"
                    value="{{ old('alamat_penjemputan', $transaksi->alamat_penjemputan) }}" required>
            </div>

            <div class="mb-4">
                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Sampah</label>
                <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full border-gray-300 rounded-md"
                    value="{{ old('jumlah', $transaksi->jumlah) }}" required min="1">
            </div>

            <div class="mb-4">
                <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700">Tanggal Penjemputan</label>
                <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="mt-1 block w-full border-gray-300 rounded-md"
                    value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi) }}" required>
            </div>

            <x-common.button>
                {{ __('Perbarui') }}
            </x-common.button>
        </form>
    </div>
</x-app-layout>