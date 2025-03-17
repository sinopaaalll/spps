<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class SiswaImport implements ToModel, WithHeadingRow, WithUpserts
{
    public function model(array $row)
    {
        return new Siswa([
            'nama' => $row['nama'],
            'jk' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'alamat' => $row['alamat'],
            'nis' => $row['nis'],
            'kelas_id' => $row['kelas'],
            'status' => $row['status'],
            'nama_ibu' => $row['nama_ibu'],
            'nama_ayah' => $row['nama_ayah'],
            'nama_wali' => $row['nama_wali'],
            'telp_ortu' => $row['telp_ortu'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function uniqueBy()
    {
        return 'nis';
    }
}
