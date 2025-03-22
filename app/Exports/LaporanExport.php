<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $bulanan;
    protected $bebas;
    protected $request;
    protected $totalPenerimaan = 0;

    public function __construct($data)
    {
        $this->bulanan = $data[0];
        $this->bebas = $data[1];
        $this->request = $data[2];
    }

    public function array(): array
    {
        $data = [];
        $no = 1;

        // Data Bulanan
        foreach ($this->bulanan as $row) {
            $this->totalPenerimaan += $row->bill; // Menjumlahkan total penerimaan
            $data[] = [
                $no++,
                $row->jenis_pembayaran->pos->nama . ' - T.A ' . $row->jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $row->jenis_pembayaran->tahun_ajaran->tahun_akhir,
                $row->siswa->nama,
                $row->siswa->kelas->nama_kelas,
                $row->tanggal,
                $row->bill,
            ];
        }

        // Data Bebas
        foreach ($this->bebas as $row) {
            $this->totalPenerimaan += $row->total_pay;
            $data[] = [
                $no++,
                $row->jenis_pembayaran->pos->nama . ' - T.A ' . $row->jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $row->jenis_pembayaran->tahun_ajaran->tahun_akhir,
                $row->siswa->nama,
                $row->siswa->kelas->nama_kelas,
                Carbon::parse($row->updated_at)->format('Y-m-d'),
                $row->total_pay,
            ];
        }

        // Menambahkan baris footer total penerimaan
        $data[] = [
            'Total Penerimaan:',
            '',
            '',
            '',
            '',
            $this->totalPenerimaan,
        ];

        return $data;
    }

    public function headings(): array
    {
        $tglAwal = Carbon::parse($this->request['tgl_awal'])->translatedFormat('d F Y');
        $tglAkhir = Carbon::parse($this->request['tgl_akhir'])->translatedFormat('d F Y');
        $tglUnduh = Carbon::now()->translatedFormat('d F Y');

        return [
            ['Laporan Keuangan'],
            ['Nama Sekolah'], // Ganti dengan nama sekolah sesuai kebutuhan
            ['Tanggal Laporan : ' . $tglAwal . ' - ' . $tglAkhir],
            ['Tanggal Unduh : ' . $tglUnduh, '', 'Pengunduh : Administrator'],
            ['No', 'Pembayaran', 'Nama Siswa', 'Kelas', 'Tanggal', 'Penerimaan'],
        ];
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    public function styles(Worksheet $sheet)
    {
        // Merge Cells untuk Judul dan Header
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:B4');
        $sheet->mergeCells('C4:F4');

        // Menentukan baris terakhir berdasarkan jumlah data
        $lastRow = 5 + count($this->bulanan) + count($this->bebas) + 1; // +1 untuk footer

        // Merge footer dari A-E untuk "Total Penerimaan"
        $sheet->mergeCells("A$lastRow:E$lastRow");

        // Atur gaya teks judul dan header
        $sheet->getStyle('A1:A4')->getFont()->setBold(true);
        $sheet->getStyle('A5:F5')->getFont()->setBold(true);

        // Tambahkan border ke header
        $sheet->getStyle('A5:F5')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Tambahkan border ke semua data
        $sheet->getStyle("A6:F$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Tambahkan gaya khusus untuk baris footer total penerimaan
        $sheet->getStyle("A$lastRow:F$lastRow")->applyFromArray([
            'font' => ['bold' => true],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Atur agar kolom menyesuaikan lebar secara otomatis
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return [
            'A1' => ['font' => ['size' => 14]], // Ukuran font judul
            'A5:F5' => ['font' => ['bold' => true]], // Header Bold
            "A$lastRow:E$lastRow" => ['alignment' => ['horizontal' => 'right']], // Footer align kanan
            "F$lastRow" => ['font' => ['bold' => true]], // Total Penerimaan Bold
        ];
    }
}
