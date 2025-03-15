<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TahunPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = TahunPelajaran::all();
            return DataTables::of($data)
                ->addColumn('aksi', function ($tahun_pelajaran) {
                    $editBtn = '<a class="btn btn-icon btn-link-warning" href="' . route('tahun_pelajaran.edit', $tahun_pelajaran->id) . '">
                <span class="ti ti-edit-circle f-18"></span>
            </a> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('tahun_pelajaran.destroy', '__ID__') . '" 
                    data-id="' . $tahun_pelajaran->id . '" 
                    data-table="tahun-pelajaran-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    return $editBtn . $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
        return view('pages.tahun-pelajaran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif'
        ];
        return view('pages.tahun-pelajaran.add', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_awal' => ['required', 'digits:4', 'integer', 'unique:tahun_pelajaran,tahun_awal'],
            'tahun_akhir' => ['required', 'digits:4', 'integer'],
            'status' => ['required'],
        ], [
            'tahun_awal.required' => 'Tahun awal wajib diisi.',
            'tahun_awal.digits' => 'Tahun awal harus terdiri dari 4 digit.',
            'tahun_awal.integer' => 'Tahun awal harus berupa angka.',
            'tahun_awal.unique' => 'Tahun awal sudah digunakan, silakan pilih tahun lain.',

            'tahun_akhir.required' => 'Tahun akhir wajib diisi.',
            'tahun_akhir.digits' => 'Tahun akhir harus terdiri dari 4 digit.',
            'tahun_akhir.integer' => 'Tahun akhir harus berupa angka.',

            'status.required' => 'Status wajib dipilih.'
        ]);

        DB::beginTransaction();
        try {
            TahunPelajaran::create($request->all());
            DB::commit();
            return redirect()->route('tahun_pelajaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tahun_pelajaran.index')->with('error', 'Data gagal disimpan');
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
        $tahun_pelajaran = TahunPelajaran::findOrFail($id);
        $status = [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif'
        ];
        return view('pages.tahun-pelajaran.edit', compact('status', 'tahun_pelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tahun_pelajaran = TahunPelajaran::findOrFail($id);
        $request->validate([
            'tahun_awal' => ['required', 'digits:4', 'integer', Rule::unique('tahun_pelajaran', 'tahun_awal')->ignore($tahun_pelajaran)],
            'tahun_akhir' => ['required', 'digits:4', 'integer'],
            'status' => ['required'],
        ], [
            'tahun_awal.required' => 'Tahun awal wajib diisi.',
            'tahun_awal.digits' => 'Tahun awal harus terdiri dari 4 digit.',
            'tahun_awal.integer' => 'Tahun awal harus berupa angka.',
            'tahun_awal.unique' => 'Tahun awal sudah digunakan, silakan pilih tahun lain.',

            'tahun_akhir.required' => 'Tahun akhir wajib diisi.',
            'tahun_akhir.digits' => 'Tahun akhir harus terdiri dari 4 digit.',
            'tahun_akhir.integer' => 'Tahun akhir harus berupa angka.',

            'status.required' => 'Status wajib dipilih.'
        ]);

        DB::beginTransaction();
        try {
            $tahun_pelajaran->update($request->all());
            DB::commit();
            return redirect()->route('tahun_pelajaran.index')->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tahun_pelajaran.index')->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tahun_pelajaran = TahunPelajaran::findOrFail($id);
        DB::beginTransaction();
        try {
            $tahun_pelajaran->delete();
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
