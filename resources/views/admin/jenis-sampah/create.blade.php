<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.jenis-sampah.index') }}"
                class="inline-flex items-center rounded-lg text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Jenis Sampah') }}</h2>
                <p class="text-sm text-gray-500">Buat jenis sampah baru yang dapat diterima di sistem</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border border-gray-200 bg-white shadow p-6">
                <form action="{{ route('admin.jenis-sampah.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama Jenis -->
                    <div class="mb-6">
                        <label for="nama_jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('Nama Jenis Sampah') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_jenis" id="nama_jenis" value="{{ old('nama_jenis') }}"
                            class="w-full rounded-lg border px-4 py-2 focus:border-emerald-500 focus:outline-none {{ $errors->has('nama_jenis') ? 'border-red-500' : 'border-gray-300'}}"
                            placeholder="Contoh: Plastik, Kertas, Logam">
                        @error('nama_jenis')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('Deskripsi') }}
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="w-full rounded-lg border px-4 py-2 focus:border-emerald-500 focus:outline-none {{ $errors->has('deskripsi') ? 'border-red-500' : 'border-gray-300'}}"
                            placeholder="Jelaskan jenis sampah ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Sampah -->
                    <div class="mb-6">
                        <label for="harga_sampah" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('Harga (Rp/kg)') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga_sampah" id="harga_sampah" value="{{ old('harga_sampah', 0) }}"
                            step="0.01" min="0"
                            class="w-full rounded-lg border px-4 py-2 focus:border-emerald-500 focus:outline-none {{ $errors->has('harga_sampah') ? 'border-red-500' : 'border-gray-300'}}"
                            placeholder="0">
                        @error('harga_sampah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar -->
                    <div class="mb-6">
                        <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('Gambar') }}
                        </label>
                        <input type="file" name="gambar" id="gambar" accept="image/*"
                            class="w-full rounded-lg border px-4 py-2 focus:border-emerald-500 focus:outline-none {{ $errors->has('gambar') ? 'border-red-500' : 'border-gray-300'}}">
                        <p class="mt-1 text-xs text-gray-500">{{ __('Format: JPEG, PNG, JPG, GIF, SVG (Max: 5MB)') }}
                        </p>
                        @error('gambar')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 justify-end pt-4">
                        <a href="{{ route('admin.jenis-sampah.index') }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-emerald-600 px-6 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                            {{ __('Simpan Jenis Sampah') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>