<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductPurchaseController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $qty = (int) $validated['quantity'];

        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Produk tidak tersedia.');
        }

        $rate = Setting::pointRateRupiahPerPoint();
        $unitPoints = (int) ceil($product->price_rupiah / $rate);
        $pointsNeeded = $unitPoints * $qty;

        try {
            DB::transaction(function () use ($product, $qty, $pointsNeeded) {
                /** @var User $user */
                $user = User::query()->whereKey(Auth::id())->lockForUpdate()->firstOrFail();

                /** @var Product $lockedProduct */
                $lockedProduct = Product::query()->whereKey($product->id)->lockForUpdate()->firstOrFail();

                if (!$lockedProduct->is_active) {
                    throw new \RuntimeException('Produk tidak tersedia.');
                }

                if ($lockedProduct->stock < $qty) {
                    throw new \RuntimeException('Stok tidak cukup.');
                }

                if (((int) $user->total_poin) < $pointsNeeded) {
                    throw new \RuntimeException('Poin Anda tidak cukup untuk membeli produk ini.');
                }

                $lockedProduct->stock = (int) $lockedProduct->stock - $qty;
                $lockedProduct->save();

                $user->total_poin = (int) $user->total_poin - $pointsNeeded;
                $user->save();

                ProductOrder::create([
                    'user_id' => $user->id,
                    'product_id' => $lockedProduct->id,
                    'quantity' => $qty,
                    'unit_price_rupiah' => (int) $lockedProduct->price_rupiah,
                    'total_price_rupiah' => (int) ($lockedProduct->price_rupiah * $qty),
                    'points_spent' => $pointsNeeded,
                    'status' => 'paid',
                ]);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Pembelian berhasil! Poin Anda telah dipotong.');
    }
}
