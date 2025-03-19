<?php

namespace App\Http\Controllers;

use App\Models\Bebas;
use App\Models\Bulan;
use App\Models\Bulanan;
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
            $data = Bebas::select(
                'bebas.*',
                'siswa.nis',
                'siswa.nama',
                'kelas.nama_kelas'
            )
                ->join('siswa', 'siswa.id', '=', 'bebas.siswa_id')
                ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
                ->where('bebas.jenis_pembayaran_id', $id)
                ->when(request('kelas_id'), function ($query) {
                    $query->where('siswa.kelas_id', request('kelas_id'));
                })
                ->orderBy('kelas.nama_kelas', 'asc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kelas', function ($bebas) {
                    return $bebas->nama_kelas ?? '-';
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

    public function payment_bulanan(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $tahun_ajaran = TahunAjaran::where('id', $jenis_pembayaran->tahun_ajaran_id)->get();
        $kelas = Kelas::all();
        return view('pages.jenis-pembayaran.bulanan', compact('jenis_pembayaran', 'tahun_ajaran', 'kelas'));
    }

    public function get(string $id)
    {
        $data = Bulanan::select(
            DB::raw('MIN(bulanan.id) as id'), // Ambil satu ID dari bulanan
            'bulanan.siswa_id',
            'siswa.nis',
            'siswa.nama',
            'kelas.nama_kelas',
        )
            ->join('siswa', 'siswa.id', '=', 'bulanan.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->where('bulanan.jenis_pembayaran_id', $id)
            ->when(request('kelas_id'), function ($query) {
                $query->where('siswa.kelas_id', request('kelas_id'));
            })
            ->groupBy('bulanan.siswa_id', 'siswa.nis', 'siswa.nama', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas', 'asc')
            ->get();

        dd($data);
    }

    public function get_payment_bulanan(string $id)
    {
        if (request()->ajax()) {
            $data = Bulanan::select(
                DB::raw('MIN(bulanan.id) as id'), // Ambil satu ID dari bulanan
                'bulanan.siswa_id',
                'bulanan.bill',
                'bulanan.jenis_pembayaran_id',
                'siswa.nis',
                'siswa.nama',
                'kelas.nama_kelas',
            )
                ->join('siswa', 'siswa.id', '=', 'bulanan.siswa_id')
                ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
                ->where('bulanan.jenis_pembayaran_id', $id)
                ->when(request('kelas_id'), function ($query) {
                    $query->where('siswa.kelas_id', request('kelas_id'));
                })
                ->groupBy('bulanan.siswa_id', 'siswa.nis', 'siswa.nama', 'kelas.nama_kelas', 'bulanan.bill', 'bulanan.jenis_pembayaran_id')
                ->orderBy('kelas.nama_kelas', 'asc')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kelas', function ($bulanan) {
                    return $bulanan->nama_kelas ?? '-';
                })
                ->addColumn('aksi', function ($bulanan) {

                    $showBtn = '<a class="btn btn-icon btn-link-info" href="' .
                        url('/jenis_pembayaran/' . $bulanan->jenis_pembayaran_id . '/payment_bulanan/' . $bulanan->siswa_id) . '">
                            <span class="ti ti-eye f-18"></span>
                        </a>';


                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                                    data-url="' . url('/jenis_pembayaran/' . '__ID__' . '/payment_bulanan') . '" 
                                    data-id="' . $bulanan->siswa_id . '" 
                                    data-table="tarif-bulanan-table">
                                    <span class="ti ti-trash f-18"></span>
                                </button>';

                    return  $showBtn . $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
    }

    public function add_payment_bulanan(string $id)
    {
        $jenis_pembayaran = JenisPembayaran::findOrFail($id);
        $tahun_ajaran = TahunAjaran::where('id', $jenis_pembayaran->tahun_ajaran_id)->get();
        $kelas = Kelas::all();
        $bulan = Bulan::all();

        return view('pages.jenis-pembayaran.payment_bulanan', compact('jenis_pembayaran', 'tahun_ajaran', 'kelas', 'bulan'));
    }

    public function show_payment_bulanan(string $jenis_pembayaran_id, string $siswa_id)
    {
        $bulanan = Bulanan::with('bulan')->where('siswa_id', $siswa_id)->get();

        // dd($bulanan);
        $jenis_pembayaran = JenisPembayaran::findOrFail($jenis_pembayaran_id);
        $tahun_ajaran = TahunAjaran::where('id', $jenis_pembayaran->tahun_ajaran_id)->get();
        $siswa = Siswa::with('kelas')->findOrFail($siswa_id);

        return view('pages.jenis-pembayaran.show_payment_bulanan', compact('bulanan', 'jenis_pembayaran', 'tahun_ajaran', 'siswa'));
    }

    public function store_payment_bulanan(Request $request, string $id)
    {
        $request->validate([
            'kelas' => ['required'],
            'bill' => ['required', 'array'],
            'bill.*' => ['required'],
        ], [
            'kelas.required' => 'Kelas tidak boleh kosong.',
            'bill.required' => 'Tarif tidak boleh kosong.',
            'bill.array' => 'Tarif harus berupa array.',
            'bill.*.required' => 'Setiap tarif tidak boleh kosong.',
        ]);

        // dd($request->all());

        $siswa = Siswa::where('kelas_id', $request->kelas)
            ->where('status', 'aktif')
            ->get();

        DB::beginTransaction();
        try {

            foreach ($siswa as $item) {
                foreach ($request->bill as $key => $value) {
                    $exists = Bulanan::where('siswa_id', $item->id)
                        ->where('bulan_id', $key)
                        ->where('jenis_pembayaran_id', $id)
                        ->exists();

                    if ($exists) {
                        // Jika ada data duplikat, rollback dan return error
                        DB::rollBack();
                        return redirect('/jenis_pembayaran/' . $id . '/payment_bulanan')
                            ->with('error', 'Data duplikasi! Siswa ini sudah memiliki tarif untuk bulan ini.');
                    }

                    Bulanan::create([
                        'bulan_id' => $key,
                        'siswa_id' => $item->id,
                        'jenis_pembayaran_id' => $id,
                        'status' => 0,
                        'bill' => (int) Str::replace(['Rp.', '.', ','], '', $value),
                        'created_at' => Carbon::now(),
                    ]);
                }
            }


            DB::commit();
            return redirect('/jenis_pembayaran/' . $id . '/payment_bulanan')->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect('/jenis_pembayaran/' . $id . '/payment_bulanan')->with('error', 'Data gagal disimpan!');
        }
    }

    public function destroy_payment_bulanan(string $id)
    {

        DB::beginTransaction();
        try {

            Bulanan::where('siswa_id', $id)->delete();

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
