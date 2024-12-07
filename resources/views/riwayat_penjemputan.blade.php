<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Penjemputan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Tabel Riwayat Penjemputan -->
                    <div class="overflow-x-auto bg-white shadow rounded-lg">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 font-medium text-gray-600">No</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Nama Nasabah</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Jenis Sampah</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Alamat Penjemputan</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Jumlah</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Tanggal Penjemputan</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Status</th>
                                    <th class="px-6 py-3 font-medium text-gray-600">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksis as $index => $transaksi)
                                <tr class="border-b border-gray-200">
                                    <td class="px-6 py-4">{{ $loop->iteration }}</td> <!-- Nomor Urut -->
                                    <td class="px-6 py-4">{{ $transaksi->user->name }}</td>
                                    <td class="px-6 py-4">{{ $transaksi->jenisSampah->nama_jenis }}</td>
                                    <td class="px-6 py-4">{{ $transaksi->alamat_penjemputan }}</td>
                                    <td class="px-6 py-4">{{ $transaksi->jumlah }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-yellow-500">{{ $transaksi->status }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('penjemputan.edit', $transaksi->transaksi_id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        |
                                        <form action="{{ route('penjemputan.destroy', $transaksi->transaksi_id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pesan jika tidak ada transaksi -->
                    @if ($transaksis->isEmpty())
                    <p class="mt-4 text-gray-600">Tidak ada riwayat penjemputan yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>