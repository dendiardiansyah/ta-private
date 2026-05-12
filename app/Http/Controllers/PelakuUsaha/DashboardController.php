<?php

namespace App\Http\Controllers\PelakuUsaha;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $pelakuUsahaId = Auth::id();

        $ordersBase = ProductOrder::query()
            ->whereHas('product', function ($q) use ($pelakuUsahaId) {
                $q->where('pelaku_usaha_id', $pelakuUsahaId);
            });

        $totalOrders = (int) (clone $ordersBase)->count();
        $totalRevenue = (int) (clone $ordersBase)->sum('total_price_rupiah');
        $totalPointsEarned = (int) (clone $ordersBase)->sum('points_spent');

        $startOfMonth = Carbon::now()->startOfMonth();
        $ordersThisMonth = (int) (clone $ordersBase)->where('created_at', '>=', $startOfMonth)->count();
        $revenueThisMonth = (int) (clone $ordersBase)->where('created_at', '>=', $startOfMonth)->sum('total_price_rupiah');

        $latestOrders = (clone $ordersBase)
            ->with(['product', 'user'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $topProducts = ProductOrder::query()
            ->join('products', 'products.id', '=', 'product_orders.product_id')
            ->where('products.pelaku_usaha_id', $pelakuUsahaId)
            ->select([
                'product_orders.product_id',
                DB::raw('SUM(product_orders.quantity) as units_sold'),
                DB::raw('SUM(product_orders.total_price_rupiah) as revenue'),
                DB::raw('COUNT(*) as orders_count'),
            ])
            ->groupBy('product_orders.product_id')
            ->orderByDesc('revenue')
            ->orderByDesc('units_sold')
            ->limit(5)
            ->get();

        $topProducts = $topProducts->load('product');

        $lowStockProducts = Product::query()
            ->where('pelaku_usaha_id', $pelakuUsahaId)
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(6)
            ->get();

        // Chart: last 14 days revenue + units
        $end = Carbon::today();
        $start = (clone $end)->subDays(13);

        $rows = ProductOrder::query()
            ->join('products', 'products.id', '=', 'product_orders.product_id')
            ->where('products.pelaku_usaha_id', $pelakuUsahaId)
            ->whereBetween(DB::raw('DATE(product_orders.created_at)'), [$start->toDateString(), $end->toDateString()])
            ->selectRaw('DATE(product_orders.created_at) as day, SUM(product_orders.total_price_rupiah) as revenue, SUM(product_orders.quantity) as units')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $perDay = $rows->keyBy('day');
        $chartLabels = [];
        $chartRevenue = [];
        $chartUnits = [];

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $day = $cursor->toDateString();
            $chartLabels[] = $cursor->format('d M');
            $chartRevenue[] = (int) ($perDay[$day]->revenue ?? 0);
            $chartUnits[] = (int) ($perDay[$day]->units ?? 0);
            $cursor->addDay();
        }

        return view('pelaku_usaha.dahsboard1', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalPointsEarned' => $totalPointsEarned,
            'ordersThisMonth' => $ordersThisMonth,
            'revenueThisMonth' => $revenueThisMonth,
            'latestOrders' => $latestOrders,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'chartLabels' => $chartLabels,
            'chartRevenue' => $chartRevenue,
            'chartUnits' => $chartUnits,
        ]);
    }
}
