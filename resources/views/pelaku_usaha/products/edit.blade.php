<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Produk') }}</h2>
            <p class="text-sm text-gray-500">Perbarui detail produk.</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('pelaku_usaha.products.update', $product) }}"
                    enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nama Produk</label>
                        <input name="name" value="{{ old('name', $product->name) }}"
                            class="mt-2 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
                        @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="mt-2 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Harga Jual (Rupiah)</label>
                            <input type="number" min="1" name="price_rupiah"
                                value="{{ old('price_rupiah', $product->price_rupiah) }}"
                                class="mt-2 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            @error('price_rupiah')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Stok</label>
                            <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}"
                                class="mt-2 w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
                            @error('stock')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1"
                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-gray-700">Aktifkan produk</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Gambar (opsional)</label>
                        @if($product->image_path)
                            <div class="mt-2 flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                    class="h-16 w-16 rounded-xl object-cover border">
                                <p class="text-xs text-gray-500">Upload file baru untuk mengganti gambar.</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="mt-2 block w-full text-sm text-gray-700" />
                        @error('image')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('pelaku_usaha.products.index') }}"
                            class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Batal</a>
                        <button type="submit"
                            class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>