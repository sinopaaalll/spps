<html>

<head>
    <title>Cetak Surat Tagihan - {{ $siswa->nama }}</title>
    <style type="text/css">
        .upper {
            text-transform: uppercase;
        }

        .lower {
            text-transform: lowercase;
        }

        .cap {
            text-transform: capitalize;
        }

        .small {
            font-variant: small-caps;
        }
    </style>
    <style type="text/css">
        @page {
            margin-top: 2cm;
            margin-bottom: 0.1em;
            margin-left: 5.0em;
            margin-right: 5.0em;
        }

        .style12 {
            font-size: 10px
        }

        .style13 {
            font-size: 14pt;
            font-weight: bold;
        }

        .title {
            font-size: 14pt;
            text-align: center;
            font-weight: bold;
            margin-bottom: -10px;
        }

        .tp {
            font-size: 12pt;
            text-align: center;
            font-weight: bold;
        }

        body {
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            font-size: 9pt;
            width: 100%;
        }
    </style>
</head>

<body>

    <p class="title">RINCIAN PEMBAYARAN ADMINISTRASI</p>
    <p class="tp"> TAHUN AJARAN {{ $tahun_ajaran->tahun_awal . '/' . $tahun_ajaran->tahun_akhir }}</p>

    <table style="font-size: 10pt;" width="100%" border="0">
        <tr>
            <td width="100">NIS</td>
            <td width="5">:</td>
            <td width="">{{ $siswa->nis }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $siswa->nama }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $siswa->kelas->nama_kelas }}</td>
        </tr>
    </table><br>

    <table width="100%" border="1" style="white-space: nowrap;">
        <tr>
            <th style="height: 30px;">NO</th>
            <th>NAMA PEMBAYARAN</th>
            <th>TANGGAL PEMBAYARAN</th>
            <th>BIAYA</th>
            <th>KETERANGAN</th>
        </tr>

        @foreach ($bulanan as $item)
            @php
                $nama_pembayaran =
                    $item->jenis_pembayaran->pos->nama .
                    ' - T.A ' .
                    $tahun_ajaran->tahun_awal .
                    '/' .
                    $tahun_ajaran->tahun_akhir;
                $month = $item->bulan_id <= 6 ? $tahun_ajaran->tahun_awal : $tahun_ajaran->tahun_akhir;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $nama_pembayaran . ' - (' . $item->bulan->nama . ' ' . $month . ')' }}</td>
                <td style="text-align: center;">
                    {{ $item->status == 1 ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-' }}
                </td>
                <td>{{ $item->status == 0 ? 'Rp. ' . number_format($item->bill, 0, ',', '.') : 'Rp. -' }}</td>
                <td>{{ $item->status == 1 ? 'Lunas' : 'Belum Lunas' }}</td>
            </tr>
        @endforeach

        @foreach ($bebas as $item)
            @php
                $nama_pembayaran_bebas =
                    $item->jenis_pembayaran->pos->nama .
                    ' - T.A ' .
                    $tahun_ajaran->tahun_awal .
                    '/' .
                    $tahun_ajaran->tahun_akhir;
            @endphp
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $nama_pembayaran_bebas }}</td>
                <td style="text-align: center;">
                    {{ $item->total_pay > 0 ? \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d F Y') : '-' }}
                </td>
                <td>
                    {{ $item->bill - $item->total_pay != 0 ? 'Rp. ' . number_format($item->bill - $item->total_pay, 0, ',', '.') : 'Rp. -' }}
                </td>
                <td> {{ $item->bill == $item->total_pay ? 'Lunas' : 'Belum Lunas' }}</td>
            </tr>
        @endforeach
    </table>


    <table style="width:100%; margin-top: 50px; font-size: 10pt; ">
        <tr>
            <td><span class="cap">Purwakarta</span>,
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>Kepala Tata Usaha</td>
        </tr>

    </table>
    <br><br><br><br>
    <table width="100%" style="font-size: 10pt;">
        <tr>
            <td><strong><u><span class="upper">( Administrator )</span></u></strong></td>
        </tr>
    </table>

</body>

</html>
