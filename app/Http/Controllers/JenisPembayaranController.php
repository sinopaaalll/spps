<?php

namespace App\Http\Controllers;

use App\Models\Bebas;
use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\Pos;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class JenisPembayaranController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = JenisPembayaran::with(['pos', 'tahun_ajaran'])->select('jenis_pembayaran.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($jenis_pembayaran) {
                    $editBtn = '<a class="btn btn-icon btn-link-warning" href="' . route('jenis_pembayaran.edit', $jenis_pembayaran->id) . '">
                                    <span class="ti ti-edit-circle f-18"></span>
                                </a> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                                    data-url="' . route('jenis_pembayaran.destroy', '__ID__') . '" 
                                    data-id="' . $jenis_pembayaran->id . '" 
                                    data-table="jenis-pembayaran-table">
                                    <span class="ti ti-trash f-18"></span>
                                </button>';

                    return $editBtn . $delBtn;
                })
                ->addColumn('setting_tarif', function ($jenis_pembayaran) {
                    if ($jenis_pembayaran->tipe == 'bebas') {
                        $url = url('jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bebas');
                    } elseif ($jenis_pembayaran->tipe == 'bulanan') {
                        $url = url('jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bulanan');
                    } else {
                        return ''; // Tidak menampilkan tombol jika tipe tidak sesuai
                    }

                    return '<a class="btn btn-primary" href="' . $url . '">
                                <span class="ti ti-settings f-18"></span> Setting Tarif Pembayaran
                            </a>';
                })
                ->rawColumns([
                    'aksi',
                    'setting_tarif'
                ])
                ->make(true);
        }
        return view('pages.jenis-pembayaran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pos = Pos::all();
        $tahun_ajaran = TahunAjaran::all();
        $tipe = [
            'bulanan' => 'bulanan',
            'bebas' => 'bebas'
        ];
        return view('pages.jenis-pembayaran.add', compact('pos', 'tahun_ajaran', 'tipe'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pos_id' => ['required'],
            'tahun_ajaran_id' => ['required', 'unique:jenis_pembayaran'],
            'tipe' => ['required'],
        ], [
            'pos_id.required' => 'Pos wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'tahun_ajaran_id.unique' => 'Tahun Ajaran sudah ada, silahkan pilih yang lain.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {

            JenisPembayaran::create($request->all());

            DB::commit();
            return redirect()->route('jenis_pembayaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jenis_pembayaran.index')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $pos = Pos::all();
        $tahun_ajaran = TahunAjaran::where('status', 'Aktif')->get();
        $tipe = [
            'bulanan' => 'bulanan',
            'bebas' => 'bebas'
        ];
        return view('pages.jenis-pembayaran.edit', compact('pos', 'tahun_ajaran', 'tipe', 'jenis_pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $request->validate([
            'pos_id' => ['required'],
            'tahun_ajaran_id' => ['required', Rule::unique('jenis_pembayaran', 'tahun_ajaran_id')->ignore($jenis_pembayaran)],
            'tipe' => ['required'],
        ], [
            'pos_id.required' => 'Pos wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
            'tahun_ajaran_id.unique' => 'Tahun Ajaran sudah ada, silahkan pilih yang lain.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        DB::beginTransaction();
        try {
            $jenis_pembayaran->update($request->all());

            DB::commit();
            return redirect()->route('jenis_pembayaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('jenis_pembayaran.index')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        DB::beginTransaction();
        try {
            $jenis_pembayaran->delete();
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

    public function payment_bebas(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $tahun_ajaran = TahunAjaran::where('id', $jenis_pembayaran->tahun_ajaran_id)->get();
        $kelas = Kelas::all();
        return view('pages.jenis-pembayaran.bebas', compact('jenis_pembayaran', 'tahun_ajaran', 'kelas'));
    }

    public function get_payment_bebas(string $id)
    {
        if (request()->ajax()) {
            $data = Bebas::with(['siswa.kelas'])
                ->where('jenis_pembayaran_id', $id)
                ->whereHas('siswa', function ($query) {
                    if (request()->has('kelas_id') && request('kelas_id') != '') {
                        $query->where('kelas_id', request('kelas_id'));
                    }
                })
                ->select('bebas.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kelas', function ($bebas) {
                    return $bebas->siswa->kelas->nama_kelas ?? '-';
                })
                ->addColumn('bill', function ($bebas) {
                    return 'Rp. ' . number_format($bebas->bill, 0, ',', '.');
                })
                ->addColumn('aksi', function ($bebas) {

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                                    data-url="' . url('/jenis_pembayaran/' . '__ID__' . '/payment_bebas') . '" 
                                    data-id="' . $bebas->id . '" 
                                    data-table="tarif-bebas-table">
                                    <span class="ti ti-trash f-18"></span>
                                </button>';

                    return  $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
    }


    public function add_payment_bebas(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $tahun_ajaran = TahunAjaran::where('id', $jenis_pembayaran->tahun_ajaran_id)->get();
        $kelas = Kelas::all();
        return view('pages.jenis-pembayaran.payment_bebas', compact('jenis_pembayaran', 'tahun_ajaran', 'kelas'));
    }

    public function store_payment_bebas(Request $request, string $id)
    {
        $request->validate([
            'kelas' => ['required'],
            'bill' => ['required'],
        ], [
            'kelas.required' => 'Kelas tidak boleh kosong.',
            'bill.required' => 'Tarif tidak boleh kosong.',
        ]);

        $siswa = Siswa::where('kelas_id', $request->kelas)
            ->where('status', 'aktif')
            ->get();

        $bill = (int) Str::replace(['Rp.', '.', ','], '', $request->bill);
        DB::beginTransaction();
        try {
            foreach ($siswa as $item) {

                $exists = Bebas::where('siswa_id', $item->id)
                    ->where('jenis_pembayaran_id', $id)
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Data duplikasi! Kelas sudah terdaftar.');
                }

                Bebas::create([
                    'siswa_id' => $item->id,
                    'jenis_pembayaran_id' => $id,
                    'bill' => $bill,
                    'created_at' => Carbon::now(),
                ]);
            }
            DB::commit();
            return redirect('/jenis_pembayaran/' . $id . '/payment_bebas')->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect('/jenis_pembayaran/' . $id . '/payment_bebas')->with('error', $th->getMessage());
        }
    }

    public function destroy_payment_bebas(string $id)
    {
        $bebas = Bebas::findOrFail($id);
        DB::beginTransaction();
        try {
            $bebas->delete();
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
}
