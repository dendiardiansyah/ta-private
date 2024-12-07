<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session()->has('message'))
                    <div class="bg-green-500 text-black p-4 rounded mb-4">
                        {{ session('message') }}
                    </div>
                    @endif


                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="jenis_sampah_id" class="block text-sm font-medium text-gray-700">Jenis Sampah</label>
                            <select name="jenis_sampah_id" id="jenis_sampah_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                <option value="">Pilih Jenis Sampah</option>
                                @foreach($jenisSampah as $jenis)
                                <option value="{{ $jenis->jenis_sampah_id }}">{{ $jenis->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="alamat_penjemputan" class="block text-sm font-medium text-gray-700">Alamat Penjemputan</label>
                            <input type="text" name="alamat_penjemputan" id="alamat_penjemputan" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah (kg)</label>
                            <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_transaksi" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi" class="mt-1 block w-full border-gray-300 rounded-md" required>
                        </div>

                        <div>
                            <x-button>
                                {{ __('Ajukan') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>