@extends('layouts.app')

@section('title', 'Tarif Bebas')
@section('breadcrumb', 'Jenis Pembayaran')
@section('breadcrumb-text', 'Data Tarif Bebas')

@section('url')
    {{ route('jenis_pembayaran.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group row">
                                <label for="tahun_ajaran_id" class="col-lg-4 col-form-label">Tahun
                                    Ajaran</label>
                                <div class="col-lg-8">
                                    <input type="text" name="" id="" class="form-control"
                                        value="{{ $jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $jenis_pembayaran->tahun_ajaran->tahun_akhir }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group row">
                                <label for="kelas_id" class="col-lg-2 col-form-label">Kelas</label>
                                <div class="col-lg-10">
                                    <select name="kelas_id" id="kelas_id" class="form-control">
                                        <option value="" selected>Semua Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->nama_kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-2">
                            <h5>Setting Tarif</h5>
                        </div>
                        <div class="col-lg-10">
                            <a href="{{ url('jenis_pembayaran/' . $jenis_pembayaran->id . '/create_payment_bebas') }}"
                                class="btn btn-primary"><span class="fa fa-plus"></span> Berdasarkan
                                Kelas</a>
                            <a href="{{ route('jenis_pembayaran.index') }}" class="btn  btn-warning"><span
                                    class="fa fa-arrow-left"></span>
                                Kembali</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="tarif-bebas-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
        <!-- [ sample-page ] end -->
    </div>




@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#tarif-bebas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/jenis_pembayaran/' . $jenis_pembayaran->id . '/get_payment_bebas') }}",
                    data: function(d) {
                        d.kelas_id = $('#kelas_id').val();
                    }
                },
                order: [
                    [4, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'siswa.nis',
                        name: 'siswa.nis'
                    },
                    {
                        data: 'siswa.nama',
                        name: 'siswa.nama'
                    },
                    {
                        data: 'kelas',
                        name: 'kelas'
                    },
                    {
                        data: 'bill',
                        name: 'bill'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Reload table saat tombol "Cari" diklik
            $(document).on('change', '#kelas_id', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
