<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use App\Models\Bulanan;
use App\Models\JenisPembayaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $siswa = null;
        $bulan = null;
        $bulanan = null;
        $jenis_pembayaran = null;
        $ta_selected = null;
        $tahun_ajaran = TahunAjaran::all();

        if ($request->t && $request->n) {
            $siswa = Siswa::with(['kelas', 'bulanan'])->where('nis', $request->n)->first();
            $ta_selected = TahunAjaran::findOrFail($request->t);
            $bulan = Bulan::all();
            $jenis_pembayaran = JenisPembayaran::where('tipe', 'bulanan')
                ->where('tahun_ajaran_id', $request->t)
                ->first();

            if (!$jenis_pembayaran) {
                return redirect()->back()->with('error', 'Jenis pembayaran tidak ditemukan.');
            }

            // Cek apakah siswa ditemukan
            if (!$siswa) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
            }


            $bulanan = Bulanan::with([
                'jenis_pembayaran.pos',
                'jenis_pembayaran.tahun_ajaran'
            ])
                ->where('jenis_pembayaran_id', $jenis_pembayaran->id)
                ->where('siswa_id', $siswa->id)
                ->get()
                ->groupBy('jenis_pembayaran_id');

            dd($bulanan);
        }

        return view("pages.payout.index", compact('tahun_ajaran', 'siswa', 'ta_selected', 'bulan', 'bulanan'));
    }

    public function bulanan_pay($siswa_id, $jenis_pembayaran_id, $bulan_id)
    {
        $bulanan = Bulanan::where('siswa_id', $siswa_id)
            ->where('jenis_pembayaran_id', $jenis_pembayaran_id)
            ->where('bulan_id', $bulan_id)
            ->firstOrFail();

        $siswa = Siswa::findOrFail($siswa_id);
        $tahunAjaranId = JenisPembayaran::where('id', $jenis_pembayaran_id)->value('tahun_ajaran_id');

        $today = Carbon::now()->format('Ymd');
        $lastNumber = Bulanan::whereDate('tanggal', Carbon::today())
            ->whereNotNull('number_pay')
            ->orderBy('number_pay', 'desc')
            ->value('number_pay');

        $nextNumber = $lastNumber ? (intval(substr($lastNumber, -4)) + 1) : 1;
        $numberPay = $today . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // dd($numberPay);

        DB::beginTransaction();
        try {
            $bulanan->update([
                'status' => 1,
                'number_pay' => $numberPay,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('payout.index', [
                't' => $tahunAjaranId,
                'n' => $siswa->nis
            ])->with('success', 'Tagihan Berhasil dibayar');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function bulanan_no_pay($siswa_id, $jenis_pembayaran_id, $bulan_id)
    {
        $bulanan = Bulanan::where('siswa_id', $siswa_id)
            ->where('jenis_pembayaran_id', $jenis_pembayaran_id)
            ->where('bulan_id', $bulan_id)
            ->firstOrFail();

        $siswa = Siswa::findOrFail($siswa_id);
        $tahunAjaranId = JenisPembayaran::where('id', $jenis_pembayaran_id)->value('tahun_ajaran_id');

        DB::beginTransaction();
        try {
            $bulanan->update([
                'status' => 0,
                'number_pay' => null,
                'tanggal' => null,
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('payout.index', [
                't' => $tahunAjaranId,
                'n' => $siswa->nis
            ])->with('success', 'Tagihan Berhasil dibatalkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
}
