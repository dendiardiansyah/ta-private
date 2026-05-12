<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Kurs Poin') }}</h2>
            <p class="text-sm text-gray-500">Atur konversi poin ke Rupiah untuk pembelian produk.</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.settings.point-rate.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nilai Rupiah per 1 Poin</label>
                        <p class="mt-1 text-xs text-gray-500">Contoh: isi <span class="font-semibold">1000</span>
                            berarti 1 Poin = Rp 1.000.</p>
                        <div class="mt-2">
                            <input type="number" name="rate" min="1" value="{{ old('rate', $rate) }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            @error('rate')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Ringkasan</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Kurs saat ini: <span class="font-semibold">1 Poin = Rp
                        {{ number_format($rate, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>