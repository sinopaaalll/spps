<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Kelas::query();
            return DataTables::of($data)
                ->addColumn('aksi', function ($kelas) {
                    $editBtn = '<button type="button" class="btn btn-icon btn-link-warning edit-kelas" data-id="' . $kelas->id . '" data-url="' . route('kelas.show', $kelas->id) . '">
                <span class="ti ti-edit-circle f-18"></span>
            </button> ';

                    $delBtn = '<button type="button" class="btn btn-icon btn-link-danger delete-kelas" data-id="' . $kelas->id . '" data-url="' . route('kelas.destroy', $kelas->id) . '">
                <span class="ti ti-trash f-18"></span>
            </button> ';

                    return $editBtn . $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
        return view('pages.kelas.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => ['required', 'unique:kelas']
        ], [
            'nama_kelas.required' => 'Kelas tidak boleh kosong.',
            'nama_kelas.unique' => 'Kelas ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            Kelas::create($request->all());
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil ditambah'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'message' => 'Data gagal ditambah'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        return response()->json(['kelas' => $kelas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $request->validate([
            'nama_kelas' => ['required', Rule::unique('kelas', 'nama_kelas')->ignore($kelas)]
        ], [
            'nama_kelas.required' => 'Kelas tidak boleh kosong.',
            'nama_kelas.unique' => 'Kelas ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            $kelas->update($request->all());
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'message' => 'Data gagal diubah'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        DB::beginTransaction();
        try {
            $kelas->delete();
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
