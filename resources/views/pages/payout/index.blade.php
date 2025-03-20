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
                            <a href="{{ route('payout.index') }}" class="btn btn-sm btn-danger">
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
                        {{-- <div class="card-tools text-end">
                            <a href="" class="btn btn-sm btn-primary">
                                <span class="fa fa-arrow-left"></span>&nbsp; Kembali
                            </a>
                        </div> --}}
                    </div>
                    <div class="card-body">

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
                                                $namaPembayaran = "{$firstItem->nama_pembayaran} T.A {$firstItem->tahun_awal}/{$firstItem->tahun_akhir}";
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
                                <p class="mb-0">It is a long established fact that a reader will be distracted by the
                                    readable
                                    content of a page when looking at its layout. The point of using Lorem Ipsum is that it
                                    has a
                                    more-or-less normal distribution of letters, as opposed to using 'Content here, content
                                    here',
                                    making it look like readable English. Many desktop publishing packages and web page
                                    editors now
                                    use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover
                                    many
                                    web sites still in their infancy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif

@endsection

@push('custom-scripts')
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
        });
    </script>
@endpush
