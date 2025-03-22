<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\Bebas;
use App\Models\Bulanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pages.laporan.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'tgl_awal' => ['required', 'date'],
            'tgl_akhir' => ['required', 'date', 'after_or_equal:tgl_awal'],
        ], [
            'tgl_awal.required' => 'Tanggal awal wajib diisi.',
            'tgl_awal.date' => 'Format tanggal awal tidak valid.',
            'tgl_akhir.required' => 'Tanggal akhir wajib diisi.',
            'tgl_akhir.date' => 'Format tanggal akhir tidak valid.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal.',
        ]);

        $bulanan = Bulanan::with(['siswa.kelas', 'jenis_pembayaran.pos', 'jenis_pembayaran.tahun_ajaran'])
            ->whereBetween('tanggal', [$request->tgl_awal, $request->tgl_akhir])
            ->get();

        $bebas = Bebas::with(['siswa.kelas', 'jenis_pembayaran.pos', 'jenis_pembayaran.tahun_ajaran'])
            ->whereBetween(DB::raw('DATE(updated_at)'), [$request->tgl_awal, $request->tgl_akhir])
            ->where(function ($query) {
                $query->whereNotNull('total_pay')
                    ->where('total_pay', '>', 0);
            })
            ->get();

        $file_name = 'Laporan Keuangan (' . Carbon::parse($request->tgl_awal)->translatedFormat('d F Y') . ' - ' . Carbon::parse($request->tgl_akhir)->translatedFormat('d F Y') . ').xlsx';

        return Excel::download(new LaporanExport([$bulanan, $bebas, $request->all()]), $file_name);
    }
}
