@extends('layouts.app')

@section('title', 'Pembayaran Siswa')
@section('breadcrumb-text', 'Pembayaran Siswa')

@section('url')
    {{ route('payout.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payout.index') }}" method="get" autocomplete="off">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group row">
                                    <label for="t" class="col-lg-4 col-form-label">Tahun
                                        Ajaran</label>
                                    <div class="col-lg-6">
                                        <select name="t" id="t" class="form-control">
                                            @foreach ($tahun_ajaran as $ta)
                                                <option value="{{ $ta->id }}"
                                                    {{ request()->t == $ta->id ? 'selected' : '' }}>
                                                    {{ $ta->tahun_awal }} / {{ $ta->tahun_akhir }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group row">
                                    <label for="n" class="col-lg-2 col-form-label">NIS</label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <input type="number" name="n" id="n" class="form-control"
                                                value="{{ request()->n }}">
                                            <button type="submit" class="btn btn-primary">Cari / Tampilkan</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- [ sample-page ] end -->
    </div>

    @if (request()->t && request()->n)
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Informasi Siswa</h5>
                        <div class="card-tools text-end">
                            @php
                                $url_print = route('payout.print', [
                                    't' => request()->t,
                                    'n' => request()->n,
                                ]);
                            @endphp
                            <a href="{{ $url_print }}" class="btn btn-sm btn-danger" target="_blank">
                                <span class="fa fa-print"></span>&nbsp; Cetak Semua Tagihan
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td>Tahun Ajaran</td>
                                <td>:</td>
                                <th>{{ $ta_selected->tahun_awal . '/' . $ta_selected->tahun_akhir }}</th>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td>:</td>
                                <td>{{ $siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td>Nama Siswa</td>
                                <td>:</td>
                                <td>{{ $siswa->nama }}</td>
                            </tr>
                            <tr>
                                <td>Nama Ibu</td>
                                <td>:</td>
                                <td>{{ $siswa->nama_ibu }}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>:</td>
                                <td>{{ $siswa->kelas->nama_kelas }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Transaksi Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pembayaran</th>
                                    <th>Tagihan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs_trx as $logs)
                                    <tr>
                                        <td>{{ $logs->pembayaran }}</td>
                                        <td>{{ 'Rp. ' . number_format($logs->bill, 0, ',', '.') }} </td>
                                        <td>{{ \Carbon\Carbon::parse($logs->tanggal)->translatedFormat('d F Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Jenis Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" id="paymentTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-uppercase" id="bulanan-tab" data-bs-toggle="tab"
                                    href="#bulanan" role="tab" aria-controls="bulanan" aria-selected="true">Bulanan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase" id="bebas-tab" data-bs-toggle="tab" href="#bebas"
                                    role="tab" aria-controls="bebas" aria-selected="false">Bebas</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="paymentTabContent">
                            <div class="tab-pane fade show active" id="bulanan" role="tabpanel"
                                aria-labelledby="bulanan-tab">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pembayaran</th>
                                            <th>Sisa Tagihan</th>
                                            @foreach ($bulan as $b)
                                                <th>{{ $b->nama }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($bulanan as $items)
                                            @php
                                                $firstItem = $items->first();
                                                $totalSemuaTagihan = $items->sum('bill');
                                                $totalSudahDibayar = $items->where('status', 1)->sum('bill');

                                                $totalTagihan = $totalSemuaTagihan - $totalSudahDibayar;
                                                $namaPembayaran = "{$firstItem->jenis_pembayaran->pos->nama} - T.A {$firstItem->jenis_pembayaran->tahun_ajaran->tahun_awal}/{$firstItem->jenis_pembayaran->tahun_ajaran->tahun_akhir}";
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $namaPembayaran }}</td>
                                                <td>{{ 'Rp. ' . number_format($totalTagihan, 0, ',', '.') }}</td>
                                                <!-- Total Tagihan -->

                                                @foreach ($bulan as $b)
                                                    @php
                                                        $bill = $items->where('bulan_id', $b->id)->first();

                                                        if ($bill) {
                                                            $status = $bill->status;
                                                            $tanggal =
                                                                '(' .
                                                                \Carbon\Carbon::parse($bill->tanggal)->format('d/m/y') .
                                                                ')';

                                                            if ($status == 0) {
                                                                $url = route('payout.bulanan.pay', [
                                                                    'siswa_id' => $firstItem->siswa_id,
                                                                    'jenis_pembayaran_id' =>
                                                                        $firstItem->jenis_pembayaran_id,
                                                                    'bulan_id' => $b->id,
                                                                ]);
                                                                $buttonClass = 'text-primary';
                                                                $buttonText = number_format($bill->bill, 0, ',', '.');
                                                                $id = 'pay';
                                                            } else {
                                                                $url = route('payout.bulanan.no_pay', [
                                                                    'siswa_id' => $firstItem->siswa_id,
                                                                    'jenis_pembayaran_id' =>
                                                                        $firstItem->jenis_pembayaran_id,
                                                                    'bulan_id' => $b->id,
                                                                ]);
                                                                $buttonClass = 'text-success';
                                                                $buttonText = $tanggal;
                                                                $id = 'no_pay';
                                                            }
                                                        }
                                                    @endphp
                                                    <td>
                                                        @if ($bill)
                                                            <form action="{{ $url }}" method="POST"
                                                                id="{{ $id }}" data-bulan="{{ $b->nama }}"
                                                                class="d-inline update-form">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="btn btn-link {{ $buttonClass }} p-0 border-0">
                                                                    {{ $buttonText }}
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                @endforeach

                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="bebas" role="tabpanel" aria-labelledby="bebas-tab">
                                @php
                                    $url_refresh = route('payout.index', [
                                        't' => request()->t,
                                        'n' => request()->n,
                                    ]);
                                @endphp
                                <a href="{{ $url_refresh }}" class="badge bg-primary"><span class="fa fa-undo"></span>
                                    Refresh</a>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pembayaran</th>
                                            <th>Tagihan</th>
                                            <th>Sisa bayar</th>
                                            <th>Dibayar</th>
                                            <th>Status</th>
                                            <th>Bayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bebas as $items)
                                            @php
                                                $url = route('payout.bebas.pay', [
                                                    'siswa_id' => $siswa->id,
                                                    'jenis_pembayaran_id' => $items->jenis_pembayaran_id,
                                                ]);

                                                $namaPembayaran = "{$items->jenis_pembayaran->pos->nama} - T.A {$items->jenis_pembayaran->tahun_ajaran->tahun_awal}/{$items->jenis_pembayaran->tahun_ajaran->tahun_akhir}";

                                                $sisaBayar = $items->bill - $items->total_pay;
                                                if ($sisaBayar == 0) {
                                                    $status = 'Lunas';
                                                    $class = 'badge bg-success';
                                                } else {
                                                    $status = 'Belum Lunas';
                                                    $class = 'badge bg-warning';
                                                }

                                                $status == 'Lunas' ? ($disable = 'disabled') : ($disable = null);
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $namaPembayaran }}</td>
                                                <td>{{ number_format($items->bill, 0, ',', '.') }}</td>
                                                <td>{{ number_format($sisaBayar, 0, ',', '.') }}</td>
                                                <td>{{ number_format($items->total_pay, 0, ',', '.') }}</td>
                                                <td>
                                                    @php
                                                        $url_detail = route('payout.bebas.detail', [
                                                            'bebas_id' => $items->id,
                                                            'siswa_id' => $items->siswa_id,
                                                            'jenis_pembayaran_id' => $items->jenis_pembayaran_id,
                                                        ]);
                                                    @endphp
                                                    <button data-url="{{ $url_detail }}"
                                                        class="detail-btn {{ $class }}">
                                                        {{ $status }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="badge bg-success bayar-btn"
                                                        data-url="{{ $url }}" data-id="{{ $items->id }}"
                                                        data-name="{{ $namaPembayaran }}"
                                                        {{ $disable }}><span></span>
                                                        Bayar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div id="bebasFormModal" class="modal modal-lg fade" tabindex="-1" role="dialog"
            aria-labelledby="bebasFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bebasFormModalLabel">Form Pembayaran Bebas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="bebasForm" action="" method="post" autocomplete="off">
                        @csrf
                        <div class="modal-body">

                            <input type="text" id="bebas_id" name="bebas_id" value="">
                            <div class="form-group row">
                                <label for="name" class="col-lg-3 col-form-label text-lg-end">Nama
                                    Pembayaran</label>
                                <div class="col-lg-9">
                                    <input type="text" id="name" class="form-control" value="" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="tanggal" class="col-lg-3 col-form-label text-lg-end">Tanggal
                                    Bayar</label>
                                <div class="col-lg-9">
                                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                                        value="{{ date('Y-m-d') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_pay" class="col-lg-3 col-form-label text-lg-end">Jumlah
                                    Dibayar*</label>
                                <div class="col-lg-9">
                                    <input type="text" id="total_pay" name="total_pay" class="form-control"
                                        value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-lg-3 col-form-label text-lg-end">Keterangan</label>
                                <div class="col-lg-9">
                                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                                        value="">
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="save">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="detailBebasFormModal" class="modal modal-lg fade" tabindex="-1" role="dialog"
            aria-labelledby="detailBebasFormModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailBebasFormModalLabel">Liha Pembayaran/Cicilan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" id="pay-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Dibayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    @endif

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/plugins/imask.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".update-form").on("submit", function(e) {
                e.preventDefault();

                let form = $(this);
                let bulan = form.data("bulan");
                let actionType = form.attr("id");
                let message = actionType === "pay" ?
                    `Apakah Anda yakin ingin melakukan pembayaran untuk bulan ${bulan}?` :
                    `Apakah Anda yakin ingin membatalkan pembayaran untuk bulan ${bulan}?`;

                Swal.fire({
                    title: "Konfirmasi",
                    text: message,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, lanjutkan!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off("submit").submit();
                    }
                });
            });

            $(document).on('click', '.bayar-btn', function() {
                let url = $(this).data('url');
                let id = $(this).data('id');
                let name = $(this).data('name');

                $('#bebasForm').attr('action', url);
                $('#bebas_id').val(id);
                $('#name').val(name);
                $('#bebasFormModal').modal('show');
            });

            var $totalPayInput = $('input[name="total_pay"]');
            $totalPayInput.on("mouseenter", function() {
                $(this).focus();
            });

            var totalPayMask = IMask($totalPayInput[0], {
                mask: 'Rp. num',
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: ','
                    }
                }
            });

            $(document).on('click', '.detail-btn', function() {
                var url = $(this).data('url');
                $('#detailBebasFormModal').modal('show');

                $('#pay-table').DataTable().destroy(); // Hancurkan DataTable sebelum reload
                $('#pay-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: url,
                        type: "GET",
                    },
                    columns: [{
                            data: "DT_RowIndex",
                            name: "DT_RowIndex"
                        },
                        {
                            data: "tanggal",
                            name: "tanggal",
                            render: function(data) {
                                if (data) {
                                    let date = new Date(data);
                                    return date.toLocaleDateString("id-ID", {
                                        day: "2-digit",
                                        month: "2-digit",
                                        year: "numeric"
                                    });
                                }
                                return "-";
                            }
                        },
                        {
                            data: "pay_bill",
                            name: "pay_bill",
                            render: function(data) {
                                return 'Rp. ' + parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: "aksi",
                            name: "aksi",
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
            });


        });
    </script>
@endpush
