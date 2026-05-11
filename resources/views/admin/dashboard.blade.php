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
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
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
                <!-- Greeting / quick context -->
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
                                Total Kg: {{ number_format($totalKg) }}
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
                            <div class="rounded-xl bg-indigo-50 p-3 text-indigo-600 group-hover:bg-indigo-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M10 10a4 4 0 100-8 4 4 0 000 8z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 16.041A10 10 0 0110 12c3.042 0 5.824 1.131 7.958 3.041A1 1 0 0117.5 17H2.5a1 1 0 01-.8-.959z"
                                        clip-rule="evenodd" />
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
                            <div class="rounded-xl bg-emerald-50 p-3 text-emerald-600 group-hover:bg-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-7.364 7.364a1 1 0 01-1.414 0L3.293 9.414a1 1 0 011.414-1.414l3.222 3.222 6.657-6.657a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
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
                            <div class="rounded-xl bg-sky-50 p-3 text-sky-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V3z" />
                                    <path d="M7 7h6v2H7V7zM7 11h6v2H7v-2z" />
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
                            <div class="rounded-xl bg-amber-50 p-3 text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M10 2a3 3 0 00-3 3v1H6a2 2 0 00-2 2v7a3 3 0 003 3h6a3 3 0 003-3V8a2 2 0 00-2-2h-1V5a3 3 0 00-3-3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
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

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Distribusi Status</h4>
                            <p class="text-xs text-gray-500">Ringkasan status transaksi</p>
                        </div>
                        <div class="mt-4 h-80">
                            <canvas id="chartStatus"></canvas>
                        </div>
                    </div>
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
                                class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua</a>
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
                                            class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
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
                                                $badge = 'bg-emerald-100 text-emerald-800';
                                            } elseif (str_contains($statusLower, 'menunggu')) {
                                                $badge = 'bg-amber-100 text-amber-800';
                                            } elseif (str_contains($statusLower, 'batal') || str_contains($statusLower, 'tolak')) {
                                                $badge = 'bg-rose-100 text-rose-800';
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
                                                {{ $t->jenisSampah->nama_jenis ?? '-' }}
                                            </td>
                                            <td class="px-3 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                                                {{ number_format((int) $t->jumlah) }}
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
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900">Update Harga Terbaru</h4>
                                <p class="text-xs text-gray-500">Menggunakan data sistem + indikator perubahan (dummy
                                    harian)
                                </p>
                            </div>
                            <a href="{{ route('admin.katalog') }}"
                                class="text-sm font-semibold text-indigo-700 hover:text-indigo-800">Katalog</a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse($latestHarga as $row)
                                @php
                                    $item = $row['model'];
                                    $delta = (int) $row['delta_percent'];
                                    $deltaClass = $delta >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50';
                                    $deltaText = ($delta >= 0 ? '+' : '') . $delta . '%';
                                @endphp
                                <div
                                    class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $item->nama_jenis }}</p>
                                        <p class="text-xs text-gray-500">Rp {{ number_format((int) $item->harga_sampah) }} /
                                            kg</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $deltaClass }}">
                                        {{ $deltaText }}
                                    </span>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-sm text-gray-500">
                                    Data jenis sampah belum tersedia.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-4 h-48">
                            <canvas id="chartPriceIndex"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">User Transaksi Tertinggi</h4>
                            <p class="text-xs text-gray-500">Berdasarkan jumlah transaksi & total kg</p>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse($topNasabah as $row)
                                <div
                                    class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">
                                            {{ $row->user->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $row->transaksi_count }} transaksi ·
                                            {{ number_format((int) $row->total_kg) }} kg
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">Top</span>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-sm text-gray-500">
                                    Belum ada data transaksi.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div>
                            <h4 class="text-base font-semibold text-gray-900">Petugas Paling Aktif</h4>
                            <p class="text-xs text-gray-500">Berdasarkan jumlah penugasan</p>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse($topPetugas as $row)
                                <div
                                    class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50 p-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">
                                            {{ $row->petugas->name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $row->transaksi_count }} tugas ·
                                            {{ number_format((int) $row->total_kg) }} kg
                                        </p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800">Aktif</span>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-sm text-gray-500">
                                    Belum ada penugasan petugas.
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
                                    backgroundColor: 'rgba(16, 185, 129, 0.25)',
                                    borderColor: 'rgba(16, 185, 129, 1)',
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    yAxisID: 'y',
                                },
                                {
                                    type: 'line',
                                    label: 'Total Kg',
                                    data: kg,
                                    borderColor: 'rgba(59, 130, 246, 1)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
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
                                    'rgba(16, 185, 129, 0.6)',
                                    'rgba(245, 158, 11, 0.6)',
                                    'rgba(59, 130, 246, 0.6)',
                                    'rgba(244, 63, 94, 0.6)',
                                    'rgba(99, 102, 241, 0.6)',
                                    'rgba(20, 184, 166, 0.6)'
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
                                borderColor: 'rgba(99, 102, 241, 1)',
                                backgroundColor: 'rgba(99, 102, 241, 0.12)',
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