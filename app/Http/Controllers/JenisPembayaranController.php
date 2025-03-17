<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\Pos;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                ->rawColumns([
                    'aksi',
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
        $tahun_ajaran = TahunAjaran::where('status', 'Aktif')->get();
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
            'pos_id' => ['required', 'unique:jenis_pembayaran'],
            'tahun_ajaran_id' => ['required'],
            'tipe' => ['required'],
        ], [
            'pos_id.required' => 'Pos wajib dipilih.',
            'pos_id.unique' => 'Pos sudah ada, silahkan pilih yang lain.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
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
            'pos_id' => ['required', Rule::unique('jenis_pembayaran', 'pos_id')->ignore($jenis_pembayaran)],
            'tahun_ajaran_id' => ['required'],
            'tipe' => ['required'],
        ], [
            'pos_id.required' => 'Pos wajib dipilih.',
            'pos_id.unique' => 'Pos sudah ada, silahkan pilih yang lain.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran wajib dipilih.',
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
}
