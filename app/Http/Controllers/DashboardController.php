<?php

namespace App\Http\Controllers;

use App\Models\Bebas;
use App\Models\Bulanan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total penerimaan hari ini
        $bulananHariIni = Bulanan::whereDate('tanggal', $today)->sum('bill');
        $bebasHariIni = Bebas::whereDate('updated_at', $today)
            ->whereNotNull('total_pay')
            ->where('total_pay', '>', 0)
            ->sum('total_pay');
        $totalPenerimaanHariIni = $bulananHariIni + $bebasHariIni;

        // Total penerimaan keseluruhan
        $totalPenerimaanBulanan = Bulanan::whereNotNull('tanggal')->sum('bill');
        $totalPenerimaanBebas = Bebas::whereNotNull('total_pay')->where('total_pay', '>', 0)->sum('total_pay');
        $totalPenerimaan = $totalPenerimaanBulanan + $totalPenerimaanBebas;

        // Total siswa aktif
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();

        return view('pages.dashboard.index', [
            'totalPenerimaanHariIni' => number_format($totalPenerimaanHariIni, 0, ',', '.'),
            'totalPenerimaan' => number_format($totalPenerimaan, 0, ',', '.'),
            'totalSiswaAktif' => number_format($totalSiswaAktif, 0, ',', '.'),
        ]);
    }
}
