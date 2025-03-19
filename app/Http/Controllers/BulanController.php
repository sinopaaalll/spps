<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class BulanController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Bulan::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($bulan) {
                    $editBtn = '<button type="button" class="btn btn-icon btn-link-warning edit-bulan" data-id="' . $bulan->id . '" data-url="' . route('bulan.show', $bulan->id) . '">
                <span class="ti ti-edit-circle f-18"></span>
            </button> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('bulan.destroy', '__ID__') . '" 
                    data-id="' . $bulan->id . '" 
                    data-table="bulan-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    return $editBtn . $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
        return view('pages.bulan.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'unique:bulan']
        ], [
            'nama.required' => 'Bulan tidak boleh kosong.',
            'nama.unique' => 'Bulan ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            Bulan::create($request->all());
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
        $bulan = Bulan::findOrFail($id);
        return response()->json(['bulan' => $bulan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bulan = Bulan::findOrFail($id);
        $request->validate([
            'nama' => ['required', Rule::unique('bulan', 'nama')->ignore($bulan)]
        ], [
            'nama.required' => 'Bulan tidak boleh kosong.',
            'nama.unique' => 'Bulan ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            $bulan->update($request->all());
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
        $bulan = Bulan::findOrFail($id);
        DB::beginTransaction();
        try {
            $bulan->delete();
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
