<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen Jenis Sampah') }}</h2>
                <p class="text-sm text-gray-500">Kelola daftar jenis sampah yang dapat diterima di sistem</p>
            </div>

            <a href="{{ route('admin.jenis-sampah.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('Tambah Jenis Sampah') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow">
                <table class="w-full text-sm">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ __('Nama Jenis') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ __('Deskripsi') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ __('Harga (Rp/kg)') }}</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">{{ __('Gambar') }}</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($jenisSampahs as $jenis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $jenis->nama_jenis }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ Str::limit($jenis->deskripsi ?? '-', 50) }}
                                </td>
                                <td class="px-4 py-3 text-gray-900">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-800">
                                        Rp {{ number_format($jenis->harga_sampah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($jenis->gambar)
                                        <img src="{{ asset('storage/' . $jenis->gambar) }}" alt="{{ $jenis->nama_jenis }}"
                                            class="h-12 w-12 rounded object-cover">
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.jenis-sampah.edit', $jenis) }}"
                                            class="inline-flex items-center rounded-md bg-amber-100 px-3 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.jenis-sampah.destroy', $jenis) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis sampah ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center rounded-md bg-rose-100 px-3 py-2 text-sm font-semibold text-rose-800 hover:bg-rose-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    {{ __('Tidak ada jenis sampah. Tambahkan jenis sampah baru.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>