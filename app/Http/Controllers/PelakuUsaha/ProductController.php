<?php

namespace App\Http\Controllers\PelakuUsaha;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function image(Product $product)
    {
        if (empty($product->image_path) || !Storage::disk('public')->exists($product->image_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($product->image_path);
    }

    public function index()
    {
        $products = Product::query()
            ->where('pelaku_usaha_id', Auth::id())
            ->orderByDesc('id')
            ->paginate(10);

        return view('pelaku_usaha.products.index', compact('products'));
    }

    public function create()
    {
        return view('pelaku_usaha.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_rupiah' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'pelaku_usaha_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price_rupiah' => (int) $validated['price_rupiah'],
            'stock' => (int) $validated['stock'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('pelaku_usaha.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        abort_unless($product->pelaku_usaha_id === Auth::id(), 403);

        return view('pelaku_usaha.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->pelaku_usaha_id === Auth::id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_rupiah' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $product->image_path = $request->file('image')->store('products', 'public');
        }

        $product->fill([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price_rupiah' => (int) $validated['price_rupiah'],
            'stock' => (int) $validated['stock'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ])->save();

        return redirect()->route('pelaku_usaha.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_unless($product->pelaku_usaha_id === Auth::id(), 403);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('pelaku_usaha.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
