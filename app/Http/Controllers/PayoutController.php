<?php

namespace App\Http\Controllers;

use App\Models\Bebas;
use App\Models\BebasPay;
use App\Models\Bulan;
use App\Models\Bulanan;
use App\Models\JenisPembayaran;
use App\Models\LogsTrx;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $tahun_ajaran = TahunAjaran::all();
        $siswa = null;
        $logs_trx = collect();;
        $bulan = Bulan::all();
        $bulanan = collect();
        $bebas = collect();
        $ta_selected = null;

        if ($request->t && $request->n) {
            $siswa = Siswa::with(['kelas', 'bulanan'])->where('nis', $request->n)->first();
            $ta_selected = TahunAjaran::find($request->t);

            if (!$siswa || !$ta_selected) {
                return redirect()->back()->with('error', 'Data siswa atau tahun ajaran tidak ditemukan.');
            }

            // Ambil Jenis Pembayaran Sekaligus
            $jenis_pembayaran = JenisPembayaran::where('tahun_ajaran_id', $request->t)->get();

            // Jika jenis pembayaran tidak ditemukan

            // Ambil Data Bulanan & Bebas
            $bulanan = Bulanan::with(['jenis_pembayaran.pos', 'jenis_pembayaran.tahun_ajaran'])
                ->whereHas('jenis_pembayaran', function ($query) use ($request) {
                    $query->where('tahun_ajaran_id', $request->t)
                        ->where('tipe', 'bulanan');
                })
                ->where('siswa_id', $siswa->id)
                ->get()
                ->groupBy('jenis_pembayaran_id');

            $bebas = Bebas::with(['jenis_pembayaran.pos', 'jenis_pembayaran.tahun_ajaran'])
                ->whereHas('jenis_pembayaran', function ($query) use ($request) {
                    $query->where('tahun_ajaran_id', $request->t)
                        ->where('tipe', 'bebas');
                })
                ->where('siswa_id', $siswa->id)
                ->get();

            $logs_trx = LogsTrx::with([
                'siswa',
                'bulanan.bulan',
                'bulanan.jenis_pembayaran.pos',
                'bulanan.jenis_pembayaran.tahun_ajaran',
                'bebas_pay.bebas.jenis_pembayaran.pos',
                'bebas_pay.bebas.jenis_pembayaran.tahun_ajaran',
            ])
                ->where('siswa_id', $siswa->id)
                ->latest('created_at')
                ->take(3)
                ->get()
                ->map(
                    function ($log) {
                        if ($log->bulanan_id && !$log->bebas_pay_id) {
                            $posNama = optional($log->bulanan->jenis_pembayaran->pos)->nama ?? '-';
                            $tahunAwal = optional($log->bulanan->jenis_pembayaran->tahun_ajaran)->tahun_awal ?? '-';
                            $tahunAkhir = optional($log->bulanan->jenis_pembayaran->tahun_ajaran)->tahun_akhir ?? '-';
                            $bulanNama = optional($log->bulanan->bulan)->nama ?? '-';

                            $log->pembayaran = "{$posNama} - T.A {$tahunAwal}/{$tahunAkhir} ({$bulanNama})";
                            $log->bill = $log->bulanan->bill;
                            $log->tanggal = $log->bulanan->tanggal;
                        } elseif ($log->bebas_pay_id && !$log->bulanan_id) {
                            $posNama = optional($log->bebas_pay->bebas->jenis_pembayaran->pos)->nama ?? '-';
                            $tahunAwal = optional($log->bebas_pay->bebas->jenis_pembayaran->tahun_ajaran)->tahun_awal ?? '-';
                            $tahunAkhir = optional($log->bebas_pay->bebas->jenis_pembayaran->tahun_ajaran)->tahun_akhir ?? '-';

                            $log->pembayaran = "{$posNama} - T.A {$tahunAwal}/{$tahunAkhir}";
                            $log->bill = $log->bebas_pay->pay_bill;
                            $log->tanggal = $log->bebas_pay->tanggal;
                        } else {
                            $log->pembayaran = '-';
                        }
                        return $log;
                    }
                );;
        }

        return view("pages.payout.index", compact('tahun_ajaran', 'siswa', 'ta_selected', 'bulan', 'bulanan', 'bebas', 'logs_trx'));
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
                'user_id' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            LogsTrx::create([
                'siswa_id' => $siswa_id,
                'bulanan_id' => $bulanan->id,
                'bebas_pay_id' => null,
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

        $logs_trx = LogsTrx::where([
            'siswa_id' => $siswa_id,
            'bulanan_id' => $bulanan->id
        ])->firstOrFail();

        DB::beginTransaction();
        try {
            $bulanan->update([
                'status' => 0,
                'number_pay' => null,
                'tanggal' => null,
                'user_id' => null,
                'updated_at' => Carbon::now(),
            ]);

            $logs_trx->delete();

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

    public function bebas_pay(Request $request, $siswa_id, $jenis_pembayaran_id)
    {
        $request->validate([
            'total_pay' => ['required']
        ]);

        $siswa = Siswa::findOrFail($siswa_id);
        $tahunAjaranId = JenisPembayaran::where('id', $jenis_pembayaran_id)->value('tahun_ajaran_id');
        $bebas = Bebas::findOrFail($request->bebas_id);

        $today = Carbon::now()->format('Ymd');
        $lastNumber = BebasPay::whereDate('tanggal', Carbon::today())
            ->whereNotNull('number_pay')
            ->orderBy('number_pay', 'desc')
            ->value('number_pay');

        $nextNumber = $lastNumber ? (intval(substr($lastNumber, -4)) + 1) : 1;
        $numberPay = $today . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $pay_bill = (int) Str::replace(['Rp.', '.', ','], '', $request->total_pay);

        DB::beginTransaction();
        try {

            $bebasPay = BebasPay::create([
                'bebas_id' => $request->bebas_id,
                'number_pay' => $numberPay,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'pay_bill' => $pay_bill,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::user()->id,
                'created_at' => Carbon::now(),
            ]);

            LogsTrx::create([
                'siswa_id' => $siswa_id,
                'bulanan_id' => null,
                'bebas_pay_id' => $bebasPay->id,
            ]);

            $totalPay = BebasPay::where('bebas_id', $request->bebas_id)->sum('pay_bill');
            $bebas->update([
                'total_pay' => $totalPay
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

    public function bebas_detail($bebas_id, $siswa_id, $jenis_pembayaran_id)
    {
        if (request()->ajax()) {
            $data = BebasPay::where('bebas_id', $bebas_id)
                ->orderBy('tanggal', 'asc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($payout) {

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('payout.bebas.destroy', '__ID__') . '" 
                    data-id="' . $payout->id . '" 
                    data-table="pay-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    return $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
    }

    public function bebas_destroy(string $id)
    {
        $bebasPay = BebasPay::findOrFail($id);
        $bebas = Bebas::with('siswa')->findOrFail($bebasPay->bebas_id);

        $logs_trx = LogsTrx::where([
            'siswa_id' => $bebas->siswa_id,
            'bebas_pay_id' => $id
        ])->firstOrFail();

        DB::beginTransaction();
        try {
            $total_pay = $bebas->total_pay - $bebasPay->pay_bill;
            $bebas->update([
                'total_pay' => $total_pay,
            ]);

            $bebasPay->delete();
            $logs_trx->delete();

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'message' => 'Data gagal dihapus'
            ]);
        }
    }

    public function print(Request $request)
    {
        $siswa = Siswa::with('kelas')->where('nis', $request->n)->firstOrFail();
        $tahun_ajaran = TahunAjaran::findOrFail($request->t);

        $bulanan = Bulanan::with(['jenis_pembayaran.pos', 'bulan'])->where([
            'siswa_id' => $siswa->id,
        ])
            ->whereHas('jenis_pembayaran', function ($query) use ($request) {
                $query->where('tahun_ajaran_id', $request->t)
                    ->where('tipe', 'bulanan');
            })->get();

        $bebas = Bebas::with(['jenis_pembayaran.pos'])->where([
            'siswa_id' => $siswa->id,
        ])
            ->whereHas('jenis_pembayaran', function ($query) use ($request) {
                $query->where('tahun_ajaran_id', $request->t)
                    ->where('tipe', 'bebas');
            })->get();


        $pdf = PDF::loadView('pages.payout.print', compact('siswa', 'tahun_ajaran', 'bulanan', 'bebas'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('Surat_Tagihan_' . $siswa->nama . '.pdf');
    }
}
