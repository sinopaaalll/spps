<?php

namespace App\Http\Controllers;

use App\Models\Pos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class PosController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = pos::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($pos) {
                    $editBtn = '<button type="button" class="btn btn-icon btn-link-warning edit-pos" data-id="' . $pos->id . '" data-url="' . route('pos.show', $pos->id) . '">
                <span class="ti ti-edit-circle f-18"></span>
            </button> ';

                    $delBtn = '<button class="btn btn-icon btn-link-danger btn-hapus" 
                    data-url="' . route('pos.destroy', '__ID__') . '" 
                    data-id="' . $pos->id . '" 
                    data-table="pos-table">
                    <span class="ti ti-trash f-18"></span>
                </button>';

                    return $editBtn . $delBtn;
                })
                ->rawColumns([
                    'aksi',
                ])
                ->make(true);
        }
        return view('pages.pos.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'unique:pos'],

        ], [
            'nama.required' => 'Nama pos tidak boleh kosong.',
            'nama.unique' => 'Nama pos ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            Pos::create($request->all());
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
        $pos = Pos::findOrFail($id);
        return response()->json(['pos' => $pos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pos = Pos::findOrFail($id);
        $request->validate([
            'nama' => ['required', Rule::unique('pos', 'nama')->ignore($pos)]
        ], [
            'nama.required' => 'Nama pos tidak boleh kosong.',
            'nama.unique' => 'Nama pos ini sudah terpakai.',
        ]);

        DB::beginTransaction();
        try {
            $pos->update($request->all());
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
        $pos = Pos::findOrFail($id);
        DB::beginTransaction();
        try {
            $pos->delete();
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
