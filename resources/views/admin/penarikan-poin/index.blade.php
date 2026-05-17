<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Penarikan Poin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3">Nasabah</th>
                                <th class="px-6 py-3">Tanggal Pengajuan</th>
                                <th class="px-6 py-3">Jumlah Poin</th>
                                <th class="px-6 py-3">Jumlah Uang</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penarikanPoin as $penarikan)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $penarikan->nasabah->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ optional($penarikan->created_at)->format('d-m-Y H:i') }}</td>
                                    <td class="px-6 py-4">{{ number_format($penarikan->jumlah_poin, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($penarikan->jumlah_uang, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @if ($penarikan->status_penarikan === 'diproses')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Diproses
                                            </span>
                                        @elseif ($penarikan->status_penarikan === 'selesai' || $penarikan->status_penarikan === 'berhasil')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($penarikan->status_penarikan) }}
                                            </span>
                                        @elseif ($penarikan->status_penarikan === 'ditolak')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Ditolak
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($penarikan->status_penarikan) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        @if($penarikan->status_penarikan === 'diproses')
                                            <form
                                                action="{{ route('admin.penarikan-poin.approve', $penarikan->penarikan_poin_id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                    Setujui
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('admin.penarikan-poin.reject', $penarikan->penarikan_poin_id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center">Tidak ada riwayat penarikan poin.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>