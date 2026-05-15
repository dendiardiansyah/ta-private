<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Katalog Sampah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            <div class="flex justify-end">
                <a href="{{ route('admin.jenis-sampah.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Tambah Jenis Sampah
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Nama Sampah</th>
                                    <th class="px-6 py-3">Deskripsi</th>
                                    <th class="px-6 py-3">Harga</th>
                                    <th class="px-6 py-3">Gambar</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenisSampahs as $jenisSampah)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4">{{ $jenisSampah->nama_jenis }}</td>
                                        <td class="px-6 py-4">{{ $jenisSampah->deskripsi }}</td>
                                        <td class="px-6 py-4">{{ $jenisSampah->harga_sampah }}</td>
                                        <td class="px-6 py-4">
                                            {{ $jenisSampah->gambar ? $jenisSampah->gambar : 'No Image' }}
                                        </td>
                                        <td class="px-6 py-4 flex space-x-2">
                                            <a href="{{ route('admin.jenis-sampah.edit', $jenisSampah->jenis_sampah_id) }}"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">Edit</a>
                                            <form
                                                action="{{ route('admin.jenis-sampah.destroy', $jenisSampah->jenis_sampah_id) }}"
                                                method="POST" onsubmit="return confirm('Yakin hapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>
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
</x-app-layout>