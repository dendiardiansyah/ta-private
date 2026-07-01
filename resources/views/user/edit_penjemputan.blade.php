<x-app-layout>
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Edit Permintaan Penjemputan</h1>

            <!-- Form Edit Penjemputan -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <form action="{{ route('penjemputan.update', $transaksi->transaksi_id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="alamat_penjemputan" class="block text-sm font-medium text-gray-700 mb-2">Alamat Penjemputan</label>
                        <textarea name="alamat_penjemputan" id="alamat_penjemputan" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100" disabled readonly>{{ Auth::user()->alamat }}</textarea>
                        <small class="text-gray-600 text-sm">Alamat diambil dari profil Anda. Ubah di halaman profil jika perlu.</small>
                    </div>

                    <div>
                        <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penjemputan</label>
                        <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="mt-1 block w-full border-gray-300 rounded-md"
                            value="{{ old('tanggal_transaksi', $transaksi->tanggal_transaksi) }}" required>
                        @error('tanggal_transaksi')
                            <small class="text-red-600">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-blue-600 flex-shrink-0 mt-0.5" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                            <small class="text-blue-900"><strong>Catatan:</strong> Anda hanya dapat mengubah tanggal penjemputan. Jenis sampah dan berat akan dicatat oleh petugas saat pengambilan.</small>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <x-common.button>
                            {{ __('Perbarui') }}
                        </x-common.button>
                        <a href="{{ route('penjemputan.history') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>