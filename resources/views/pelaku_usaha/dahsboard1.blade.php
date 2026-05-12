<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard Pelaku Usaha') }}</h2>
                <p class="text-sm text-gray-500">Ringkasan penjualan produk Anda dan aktivitas pembelian.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('pelaku_usaha.products.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V3z" />
                        <path d="M7 7h6v2H7V7zM7 11h6v2H7v-2z" />
                    </svg>
                    Kelola Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Summary cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total Penjualan</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">Rp
                        {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-500">Total dari semua order</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Order Masuk</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($totalOrders) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">Semua waktu</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Penjualan Bulan Ini</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">Rp
                        {{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-500">Order bulan ini: {{ number_format($ordersThisMonth) }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Poin Dibayar (Total)</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">
                        {{ number_format($totalPointsEarned) }}</p>
                    <p class="mt-1 text-xs text-gray-500">Akumulasi poin yang dibelanjakan user</p>
                </div>
            </div>

            <!-- Chart + side panels -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Tren Penjualan (14 Hari)</h3>
                        <p class="text-xs text-gray-500">Pendapatan (Rp) dan unit terjual per hari</p>
                    </div>
                    <div class="mt-4 h-80">
                        <canvas id="chartSales"></canvas>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Produk Terlaris</h3>
                            <p class="text-xs text-gray-500">Top 5 berdasarkan revenue</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse($topProducts as $row)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">
                                            {{ $row->product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format((int) $row->orders_count) }} order
                                            · {{ number_format((int) $row->units_sold) }} unit</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        Rp {{ number_format((int) $row->revenue, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-xl border border-dashed border-gray-200 p-4 text-center text-sm text-gray-500">
                                Belum ada penjualan.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-900">Stok Menipis</h4>
                        <div class="mt-3 space-y-2">
                            @forelse($lowStockProducts as $p)
                                <div class="flex items-center justify-between rounded-lg border border-gray-100 px-3 py-2">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $p->name }}</p>
                                    <span class="text-xs font-semibold text-amber-700">Stok:
                                        {{ number_format($p->stock) }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Tidak ada data produk.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Latest orders -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Order Terbaru</h3>
                    <p class="text-xs text-gray-500">Hanya order untuk produk milik Anda</p>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase text-gray-500">
                            <tr class="border-b">
                                <th class="px-3 py-2 text-left">Waktu</th>
                                <th class="px-3 py-2 text-left">Produk</th>
                                <th class="px-3 py-2 text-right">Qty</th>
                                <th class="px-3 py-2 text-right">Total (Rp)</th>
                                <th class="px-3 py-2 text-right">Poin</th>
                                <th class="px-3 py-2 text-left">Pembeli</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($latestOrders as $o)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-3 text-gray-700 whitespace-nowrap">
                                        {{ optional($o->created_at)->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $o->product->name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                                        {{ number_format((int) $o->quantity) }}
                                    </td>
                                    <td class="px-3 py-3 text-right whitespace-nowrap">
                                        Rp {{ number_format((int) $o->total_price_rupiah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-right whitespace-nowrap">
                                        {{ number_format((int) $o->points_spent) }}
                                    </td>
                                    <td class="px-3 py-3 text-gray-700 whitespace-nowrap">
                                        {{ $o->user->name ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const labels = @json($chartLabels);
                const revenue = @json($chartRevenue);
                const units = @json($chartUnits);

                const canvas = document.getElementById('chartSales');
                if (!canvas) return;

                new Chart(canvas, {
                    data: {
                        labels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Pendapatan (Rp)',
                                data: revenue,
                                backgroundColor: 'rgba(16, 185, 129, 0.25)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1,
                                borderRadius: 8,
                                yAxisID: 'y',
                            },
                            {
                                type: 'line',
                                label: 'Unit Terjual',
                                data: units,
                                borderColor: 'rgba(59, 130, 246, 1)',
                                backgroundColor: 'rgba(59, 130, 246, 0.12)',
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
                            legend: { position: 'bottom' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.04)' },
                                ticks: {
                                    callback: function (value) {
                                        try {
                                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                                        } catch (e) {
                                            return value;
                                        }
                                    }
                                }
                            },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>