<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="text-sm text-gray-500">Ringkasan aktivitas, approval, dan performa sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.approvals.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Approval
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8z" />
                        <path fill-rule="evenodd"
                            d="M.458 16.041A10 10 0 0110 12c3.042 0 5.824 1.131 7.958 3.041A1 1 0 0117.5 17H2.5a1 1 0 01-.8-.959z"
                            clip-rule="evenodd" />
                    </svg>
                    Users
                </a>
            </div>
        </div>
    </x-slot>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Selamat datang,</p>
                            <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                Total Transaksi: {{ number_format($totalTransaksi) }}
                            </span>
                            <span
                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                Total Kg: {{ number_format($totalKg, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                                    {{ number_format($totalUsers) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Approved: {{ number_format($approvedUsers) }} ·
                                    Pending:
                                    {{ number_format($pendingUsers) }}
                                </p>
                            </div>
                            <div class="rounded-xl bg-gray-100 p-3 text-gray-600 group-hover:bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216Z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('admin.approvals.index') }}"
                        class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Approval</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                                    {{ number_format($pendingApprovalsCount) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Non-nasabah menunggu persetujuan</p>
                            </div>
                            <div class="rounded-xl bg-amber-50 p-3 text-amber-600 group-hover:bg-amber-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Aktivitas Transaksi</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                                    {{ number_format($totalTransaksi) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Nasabah aktif:
                                    {{ number_format($activeNasabahCount) }}
                                </p>
                            </div>
                            <div class="rounded-xl bg-gray-100 p-3 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Petugas Aktif</p>
                                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                                    {{ number_format($activePetugasCount) }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">Berdasarkan penugasan transaksi</p>
                            </div>
                            <div class="rounded-xl bg-gray-100 p-3 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                    <path fill-rule="evenodd" d="M10.646 6.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L12.293 9l-1.647-1.646a.5.5 0 0 1 0-.708Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Charts + Waste Type Table -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
                    <!-- Chart Transaksi -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">Aktivitas Transaksi (14 Hari)</h4>
                                <p class="text-xs text-gray-500">Jumlah transaksi & total kg per hari</p>
                            </div>
                        </div>
                        <div class="mt-4 h-80">
                            <canvas id="chartTransaksi"></canvas>
                        </div>
                    </div>
                    <!-- Pie Chart Status -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Distribusi Status</h4>
                            <p class="text-xs text-gray-500">Ringkasan status transaksi</p>
                        </div>
                        <div class="mt-4 h-80">
                            <canvas id="chartStatus"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Total per Jenis Sampah - Cards Style -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Total per Jenis Sampah</h4>
                            <p class="text-xs text-gray-500">Akumulasi sampah yang sudah berhasil dikumpulkan (Status: Selesai)</p>
                        </div>
                        <a href="{{ route('admin.jenis-sampah.index') }}"
                            class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-gray-900">
                            Kelola
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </a>
                    </div>
                    @if($totalPerJenisSampah->count() > 0)
                        <!-- Cards Grid -->
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach($totalPerJenisSampah as $item)
                                <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-4 hover:shadow-md transition-all">
                                    <!-- Icon Badge -->
                                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gray-200 opacity-20 group-hover:opacity-30 transition-opacity"></div>
                                    <div class="relative">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="rounded-lg bg-gray-100 p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h5 class="font-bold text-gray-900 mb-1">{{ $item->nama_jenis }}</h5>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-2xl font-bold text-gray-900">{{ number_format($item->total_kg, 1) }}</span>
                                            <span class="text-sm font-semibold text-gray-500">kg</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">
                                            Avg: <span class="font-semibold text-gray-700">{{ number_format($item->total_kg / $item->transaksi_count, 2) }} kg</span> /transaksi
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Total Summary Card (satu-satunya aksen kuat di section ini) -->
                        <div class="mt-4 rounded-xl bg-emerald-600 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-white mb-1">TOTAL KESELURUHAN</p>
                                    <p class="text-xs text-white">Semua jenis sampah yang sudah dikumpulkan</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-baseline gap-1 justify-end">
                                        <span class="text-3xl font-bold text-white">{{ number_format($totalPerJenisSampah->sum('total_kg'), 1) }}</span>
                                        <span class="text-sm font-bold text-white">kg</span>
                                    </div>
                                    <p class="text-xs text-white mt-1">
                                        dari {{ number_format($totalPerJenisSampah->sum('transaksi_count')) }} transaksi
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Belum Ada Data</p>
                            <p class="text-xs text-gray-500">Transaksi dengan status "Selesai" akan muncul di sini</p>
                        </div>
                    @endif
                </div>
                <!-- Content grid -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <!-- Latest approvals -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">Latest Approval</h4>
                                <p class="text-xs text-gray-500">Pendaftar non-nasabah menunggu persetujuan</p>
                            </div>
                            <a href="{{ route('admin.approvals.index') }}"
                                class="text-sm font-semibold text-gray-700 hover:text-gray-900">Lihat semua</a>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse($pendingApprovals as $u)
                                <div
                                    class="flex items-start justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $u->name }}</p>
                                        <p class="truncate text-xs text-gray-500">{{ $u->email }}</p>
                                        <p class="mt-1 text-xs text-gray-600">Role: {{ $u->roles->first()->name ?? '-' }}
                                        </p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.approvals.approve', $u->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-800">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-sm text-gray-500">
                                    Tidak ada pendaftar pending.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Latest activity -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">Aktivitas Terbaru</h4>
                                <p class="text-xs text-gray-500">Transaksi terbaru yang tercatat di sistem</p>
                            </div>
                        </div>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase text-gray-500">
                                    <tr class="border-b">
                                        <th class="px-3 py-2 text-left">Tanggal</th>
                                        <th class="px-3 py-2 text-left">Nasabah</th>
                                        <th class="px-3 py-2 text-left">Jenis</th>
                                        <th class="px-3 py-2 text-right">Kg</th>
                                        <th class="px-3 py-2 text-left">Petugas</th>
                                        <th class="px-3 py-2 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse($latestTransaksi as $t)
                                        @php
                                            $status = (string) ($t->status ?? '-');
                                            $statusLower = strtolower($status);
                                            $badge = 'bg-gray-100 text-gray-700';
                                            if (str_contains($statusLower, 'selesai') || str_contains($statusLower, 'berhasil')) {
                                                $badge = 'bg-emerald-50 text-emerald-700';
                                            } elseif (str_contains($statusLower, 'menunggu')) {
                                                $badge = 'bg-amber-50 text-amber-700';
                                            } elseif (str_contains($statusLower, 'batal') || str_contains($statusLower, 'tolak')) {
                                                $badge = 'bg-rose-50 text-rose-700';
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-3 text-gray-700 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d M Y') }}
                                            </td>
                                            <td class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap">
                                                {{ $t->user->name ?? '-' }}
                                            </td>
                                            <td class="px-3 py-3 text-gray-700 whitespace-nowrap">
                                                @if($t->details->isNotEmpty())
                                                    <div class="d-flex flex-column gap-1">
                                                        @foreach($t->details as $detail)
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge bg-success">{{ $detail->jenisSampah->nama_jenis ?? '-' }}</span>
                                                                <small class="text-muted">{{ number_format($detail->berat, 2) }} kg</small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                                                {{ number_format($t->total_berat, 2) }} kg
                                            </td>
                                            <td class="px-3 py-3 text-gray-700 whitespace-nowrap">
                                                {{ $t->petugas->name ?? '-' }}
                                            </td>
                                            <td class="px-3 py-3 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada transaksi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Price updates + Top performers -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <!-- Update Harga Terbaru -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">Update Harga Terbaru</h4>
                                <p class="text-xs text-gray-500">Harga per kg & perubahan harian</p>
                            </div>
                            <a href="{{ route('admin.jenis-sampah.index') }}"
                                class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/>
                                </svg>
                            </a>
                        </div>
                        <div class="space-y-2">
                            @forelse($latestHarga as $row)
                                @php
                                    $item = $row['model'];
                                    $delta = (int) $row['delta_percent'];
                                    $isPositive = $delta >= 0;
                                    $deltaClass = $isPositive ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50';
                                    $deltaText = ($isPositive ? '+' : '') . $delta . '%';
                                    $iconColor = $isPositive ? 'text-emerald-600' : 'text-rose-600';
                                @endphp
                                <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-3 hover:shadow-md transition-all">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="rounded-lg bg-gray-100 p-2 flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                                    <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $item->nama_jenis }}</p>
                                                <p class="text-xs text-gray-500">Rp {{ number_format((int) $item->harga_sampah) }}/kg</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $iconColor }}" fill="currentColor" viewBox="0 0 16 16">
                                                @if($isPositive)
                                                    <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
                                                @else
                                                    <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                                                @endif
                                            </svg>
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-bold {{ $deltaClass }}">
                                                {{ $deltaText }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 mb-2" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">Data jenis sampah belum tersedia</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4 h-48">
                            <canvas id="chartPriceIndex"></canvas>
                        </div>
                    </div>
                    <!-- User Transaksi Tertinggi -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-4">
                            <h4 class="text-base font-semibold text-gray-900">User Transaksi Tertinggi</h4>
                            <p class="text-xs text-gray-500">Top 5 nasabah paling aktif</p>
                        </div>
                        <div class="space-y-2">
                            @forelse($topNasabah as $index => $row)
                                <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-3 hover:shadow-md transition-all">
                                    <div class="flex items-center gap-3">
                                        <!-- Rank Badge -->
                                        <div class="flex-shrink-0">
                                            <div class="rounded-full bg-gray-100 w-10 h-10 flex items-center justify-center">
                                                <span class="text-lg font-bold text-gray-700">#{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                        <!-- User Info -->
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-gray-900 text-sm truncate">
                                                {{ $row->user->name ?? '-' }}
                                            </p>
                                            <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                                    </svg>
                                                    {{ $row->transaksi_count }}x
                                                </span>
                                                <span class="flex items-center gap-1 font-semibold text-gray-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113z"/>
                                                        <path d="M15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24z"/>
                                                    </svg>
                                                    {{ number_format((int) $row->total_kg) }} kg
                                                </span>
                                            </div>
                                        </div>
                                        @if($index === 0)
                                            <div class="flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
                                                    <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 mb-2" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7z"/>
                                        <path fill-rule="evenodd" d="M11 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">Belum ada data transaksi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Petugas Paling Aktif -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-4">
                            <h4 class="text-base font-semibold text-gray-900">Petugas Paling Aktif</h4>
                            <p class="text-xs text-gray-500">Top 5 petugas dengan penugasan terbanyak</p>
                        </div>
                        <div class="space-y-2">
                            @forelse($topPetugas as $index => $row)
                                <div class="group relative overflow-hidden rounded-xl border border-gray-100 bg-gray-50 p-3 hover:shadow-md transition-all">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar/Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="rounded-full bg-gray-100 w-10 h-10 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Petugas Info -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900 text-sm truncate">
                                                    {{ $row->petugas->name ?? '-' }}
                                                </p>
                                                @if($index === 0)
                                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-800">
                                                        MVP
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                                <span class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                                                    </svg>
                                                    {{ $row->transaksi_count }} tugas
                                                </span>
                                                <span class="flex items-center gap-1 font-semibold text-gray-800">
                                                    {{ number_format((int) $row->total_kg) }} kg
                                                </span>
                                            </div>
                                        </div>
                                        <!-- Progress indicator -->
                                        <div class="flex-shrink-0">
                                            <div class="text-right">
                                                <span class="text-xs font-bold text-gray-500">#{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 mb-2" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                    </svg>
                                    <p class="text-xs text-gray-500">Belum ada penugasan petugas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const labels = @json($transaksiChartLabels);
                const counts = @json($transaksiChartCounts);
                const kg = @json($transaksiChartKg);
                const statusLabels = @json($transaksiByStatus->keys()->values());
                const statusData = @json($transaksiByStatus->values());
                const priceLabels = @json($priceIndexLabels);
                const priceIndex = @json($priceIndexData);
                const transaksiCanvas = document.getElementById('chartTransaksi');
                if (transaksiCanvas) {
                    new Chart(transaksiCanvas, {
                        data: {
                            labels,
                            datasets: [
                                {
                                    type: 'bar',
                                    label: 'Transaksi',
                                    data: counts,
                                    backgroundColor: 'rgba(107, 114, 128, 0.25)',
                                    borderColor: 'rgba(75, 85, 99, 1)',
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    yAxisID: 'y',
                                },
                                {
                                    type: 'line',
                                    label: 'Total Kg',
                                    data: kg,
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    backgroundColor: 'rgba(16, 185, 129, 0.10)',
                                    tension: 0.35,
                                    fill: true,
                                    pointRadius: 3,
                                    yAxisID: 'y1',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.04)' },
                                    ticks: { precision: 0 },
                                },
                                y1: {
                                    beginAtZero: true,
                                    position: 'right',
                                    grid: { drawOnChartArea: false },
                                    ticks: { precision: 0 },
                                }
                            }
                        }
                    });
                }
                const statusCanvas = document.getElementById('chartStatus');
                if (statusCanvas) {
                    new Chart(statusCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusData,
                                backgroundColor: [
                                    'rgba(75, 85, 99, 0.7)',
                                    'rgba(156, 163, 175, 0.7)',
                                    'rgba(16, 185, 129, 0.6)',
                                    'rgba(245, 158, 11, 0.6)',
                                    'rgba(244, 63, 94, 0.5)',
                                    'rgba(107, 114, 128, 0.4)'
                                ],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            cutout: '68%'
                        }
                    });
                }
                const priceCanvas = document.getElementById('chartPriceIndex');
                if (priceCanvas) {
                    new Chart(priceCanvas, {
                        type: 'line',
                        data: {
                            labels: priceLabels,
                            datasets: [{
                                label: 'Indeks Harga (dummy)',
                                data: priceIndex,
                                borderColor: 'rgba(75, 85, 99, 1)',
                                backgroundColor: 'rgba(75, 85, 99, 0.10)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: { grid: { display: false } },
                                y: { grid: { color: 'rgba(0,0,0,0.04)' } }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>