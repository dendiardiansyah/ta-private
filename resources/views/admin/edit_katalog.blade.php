<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jenis Sampah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <form action="{{ route('admin.katalog.update', $jenisSampah->jenis_sampah_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Jenis Sampah</label>
                            <input type="text" name="nama_jenis" value="{{ $jenisSampah->nama_jenis }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>{{ $jenisSampah->deskripsi }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Estimasi Harga (/kg)</label>
                            <input type="number" name="harga_sampah" value="{{ $jenisSampah->harga_sampah }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Gambar</label>
                            @if($jenisSampah->gambar)
                                <div class="mb-2 text-sm text-gray-500">Gambar saat ini: {{ $jenisSampah->gambar }}</div>
                            @endif
                            <input type="file" name="gambar"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.katalog') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded shadow-sm hover:bg-gray-600">Batal</a>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded shadow-sm hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>