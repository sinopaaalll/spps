<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = TahunAjaran::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($tahun_ajaran) {
                    $editBtn = '<a class="btn btn-icon btn-link-warning" href="' . route('tahun_ajaran.edit', $tahun_ajaran->id) . '">
                <span class="ti ti-edit-circle f-18"></span>
            </a> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('tahun_ajaran.destroy', '__ID__') . '" 
                    data-id="' . $tahun_ajaran->id . '" 
                    data-table="tahun-ajaran-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    $activeBtn = $tahun_ajaran->status == 'Tidak Aktif'
                        ? '<form action="' . route('tahun_ajaran.active', $tahun_ajaran->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('PUT') . '
                        <button type="submit" class="btn btn-icon btn-link-success">
                            <span class="ti ti-check f-18"></span>
                        </button>
                    </form>'
                        : '';

                    return $editBtn . $delBtn . $activeBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
        return view('pages.tahun-ajaran.index');
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
        return view('pages.tahun-ajaran.add', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_awal' => ['required', 'digits:4', 'integer', 'unique:tahun_ajaran,tahun_awal'],
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

            TahunAjaran::create($request->all());

            if ($request->status) {
                TahunAjaran::query()->update(['status' => 'Tidak Aktif']);
            }

            DB::commit();
            return redirect()->route('tahun_ajaran.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tahun_ajaran.index')->with('error', 'Data gagal disimpan');
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
        $tahun_ajaran = TahunAjaran::findOrFail($id);
        $status = [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif'
        ];
        return view('pages.tahun-ajaran.edit', compact('status', 'tahun_ajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tahun_ajaran = TahunAjaran::findOrFail($id);
        $request->validate([
            'tahun_awal' => ['required', 'digits:4', 'integer', Rule::unique('tahun_ajaran', 'tahun_awal')->ignore($tahun_ajaran)],
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

            if ($request->has('status')) {
                return redirect()->back()->with('error', 'Status tidak dapat diubah.');
            }
            // Update tanpa mengubah status
            $tahun_ajaran->update($request->except('status'));

            DB::commit();
            return redirect()->route('tahun_ajaran.index')->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tahun_ajaran.index')->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tahun_ajaran = TahunAjaran::findOrFail($id);
        DB::beginTransaction();
        try {
            $tahun_ajaran->delete();
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

    public function active(string $id)
    {
        DB::beginTransaction();
        try {
            // Reset semua status menjadi "Tidak Aktif"
            TahunAjaran::query()->update(['status' => 'Tidak Aktif']);

            // Aktifkan data yang dipilih
            $tahun_ajaran = TahunAjaran::findOrFail($id);
            $tahun_ajaran->update(['status' => 'Aktif']);

            DB::commit();
            return redirect()->route('tahun_ajaran.index')->with('success', 'Tahun pelajaran berhasil diaktifkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('tahun_ajaran.index')->with('error', 'Gagal mengaktifkan tahun pelajaran');
        }
    }
}
