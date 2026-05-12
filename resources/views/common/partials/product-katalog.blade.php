@php
    $rate = max(1, (int) ($pointRate ?? 1000));
    $currentPoints = (int) (auth()->user()?->total_poin ?? 0);
@endphp

<div class="mb-4">
    <h3 class="fw-bold text-success mb-1">Katalog Produk</h3>
    <p class="text-muted mb-0">Harga produk ditampilkan dalam Rupiah dan konversi poin (kurs: 1 Poin =
        Rp{{ number_format($rate, 0, ',', '.') }}).</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@elseif(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@auth
    <div class="alert alert-light border mb-4">
        Saldo Poin Anda saat ini: <strong>{{ number_format($currentPoints) }}</strong>
    </div>
@endauth

<div class="row g-4">
    @forelse ($products as $product)
        @php
            $unitPoints = (int) ceil(((int) $product->price_rupiah) / $rate);
            $outOfStock = ((int) $product->stock) <= 0;
        @endphp
        <div class="col-sm-6 col-lg-4">
            <div class="card soft-card h-100">
                <div class="katalog-image-wrap">
                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : asset('image/default.png') }}"
                        class="katalog-image" alt="{{ $product->name }}"
                        onerror="this.onerror=null;this.src='{{ asset('image/default.png') }}'">
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-success fw-bold mb-2">{{ $product->name }}</h5>
                    @if($product->description)
                        <p class="card-text text-muted mb-3">{{ $product->description }}</p>
                    @else
                        <p class="card-text text-muted mb-3">Produk hasil pengolahan sampah.</p>
                    @endif

                    <div class="mt-auto">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <span class="price-chip">Rp{{ number_format($product->price_rupiah, 0, ',', '.') }}</span>
                            <span class="badge bg-success-subtle text-success border">{{ number_format($unitPoints) }}
                                poin</span>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <small class="text-muted">Stok: {{ number_format($product->stock) }}</small>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('katalog.products.buy', $product) }}"
                                class="mt-3 d-flex gap-2 align-items-center">
                                @csrf
                                <input type="number" name="quantity" min="1" value="1" class="form-control form-control-sm"
                                    style="max-width: 90px;" {{ $outOfStock ? 'disabled' : '' }}>
                                @php
                                    $canBuy = (!$outOfStock) && ($currentPoints >= $unitPoints);
                                @endphp
                                <button type="submit" class="btn btn-sm btn-primary" {{ $outOfStock ? 'disabled' : '' }}>
                                    Beli
                                </button>
                            </form>
                            @if($outOfStock)
                                <small class="text-danger d-block mt-2">Stok habis.</small>
                            @elseif($currentPoints < $unitPoints)
                                <small class="text-danger d-block mt-2">Poin Anda tidak cukup (minimal
                                    {{ number_format($unitPoints) }} poin untuk 1 produk).</small>
                            @endif
                        @else
                            <div class="mt-3">
                                <a class="btn btn-sm btn-outline-success" href="{{ route('login') }}">Login untuk membeli</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center mb-0">
                Produk belum tersedia.
            </div>
        </div>
    @endforelse
</div>