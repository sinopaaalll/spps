<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            // All
            $query = Siswa::with('kelas');

            // Filter berdasarkan NIS
            if (request()->nis) {
                $query->where('nis', 'like', '%' . request()->nis . '%');
            }

            // Filter berdasarkan Nama
            if (request()->nama) {
                $query->where('nama', 'like', '%' . request()->nama . '%');
            }

            // Filter berdasarkan Kelas
            if (request()->kelas_id) {
                $query->where('kelas_id', request()->kelas_id);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_kelas', function ($siswa) {
                    return $siswa->kelas ? $siswa->kelas->nama_kelas : '-';
                })
                ->addColumn('aksi', function ($siswa) {
                    $lihatBtn = '<a class="btn btn-icon btn-link-info" href="' . route('siswa.show', $siswa->id) . '">
                                    <span class="ti ti-eye f-18"></span>
                                </a> ';
                    $editBtn = '<a class="btn btn-icon btn-link-warning" href="' . route('siswa.edit', $siswa->id) . '">
                                    <span class="ti ti-edit-circle f-18"></span>
                                </a> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('siswa.destroy', '__ID__') . '" 
                    data-id="' . $siswa->id . '" 
                    data-table="siswa-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    return $lihatBtn . $editBtn . $delBtn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $kelas = Kelas::all();
        return view('pages.siswa.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $jk = [
            'Laki-laki' => 'Laki-laki',
            'Perempuan' => 'Perempuan'
        ];
        $status = [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif'
        ];

        return view('pages.siswa.add', compact('kelas', 'jk', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required'],
            'jk' => ['required'],
            'tempat_lahir' => ['nullable'],
            'tanggal_lahir' => ['nullable'],
            'alamat' => ['nullable'],
            'nis' => ['required', 'numeric', 'unique:siswa'],
            'kelas_id' => ['required'],
            'status' => ['required'],
            'nama_ibu' => ['nullable'],
            'nama_ayah' => ['nullable'],
            'nama_wali' => ['nullable'],
            'telp_ortu' => ['nullable', 'numeric']
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'jk.required' => 'Jenis kelamin wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'kelas.required' => 'Kelas wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'telp_ortu.numeric' => 'Nomor telepon orang tua harus berupa angka.'
        ]);

        DB::beginTransaction();
        try {
            Siswa::create($request->all());
            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->route('siswa.index')->with('error', 'Data gagal disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);

        return view('pages.siswa.view', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        $kelas = Kelas::all();
        $jk = [
            'Laki-laki' => 'Laki-laki',
            'Perempuan' => 'Perempuan'
        ];
        $status = [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif'
        ];

        return view('pages.siswa.edit', compact('siswa', 'kelas', 'jk', 'status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => ['required'],
            'jk' => ['required'],
            'tempat_lahir' => ['nullable'],
            'tanggal_lahir' => ['nullable'],
            'alamat' => ['nullable'],
            'nis' => ['required', 'numeric', Rule::unique('siswa', 'nis')->ignore($siswa)],
            'kelas_id' => ['required'],
            'status' => ['required'],
            'nama_ibu' => ['nullable'],
            'nama_ayah' => ['nullable'],
            'nama_wali' => ['nullable'],
            'telp_ortu' => ['nullable', 'numeric']
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'jk.required' => 'Jenis kelamin wajib diisi.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'kelas.required' => 'Kelas wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'telp_ortu.numeric' => 'Nomor telepon orang tua harus berupa angka.'
        ]);

        DB::beginTransaction();
        try {
            $siswa->update($request->all());
            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()->route('siswa.index')->with('error', 'Data gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        DB::beginTransaction();
        try {
            $siswa->delete();
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

    public function import()
    {
        Excel::import(new SiswaImport, 'Siswa_import.xlsx');
        return 1;
    }
}
