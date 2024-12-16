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
                                    <th class="px-6 py-3 font-medium text-gray-600">Poin</th>
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
                                        @php
                                        // Menghitung jumlah poin berdasarkan transaksi
                                        $totalPoin = $transaksi->poin->sum('jumlah_poin');
                                        @endphp
                                        {{ $totalPoin > 0 ? $totalPoin . ' Poin' : '-' }}
                                    </td>
                                    <td class="px-6 py-4 flex items-center space-x-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('penjemputan.edit', $transaksi->transaksi_id) }}" class="text-blue-600 hover:text-blue-800 me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>

                                        <!-- Pemisah -->
                                        <span class="text-gray-500">|</span>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('penjemputan.destroy', $transaksi->transaksi_id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 ms-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
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