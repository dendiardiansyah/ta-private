<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PointRateController extends Controller
{
    public function edit()
    {
        $rate = Setting::pointRateRupiahPerPoint();

        return view('admin.settings.point-rate', [
            'rate' => $rate,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // rupiah per 1 poin
            'rate' => ['required', 'integer', 'min:1'],
        ]);

        Setting::setInt('point_rate_rp_per_point', (int) $validated['rate']);

        return redirect()
            ->route('admin.settings.point-rate.edit')
            ->with('success', 'Kurs poin berhasil diperbarui.');
    }
}
