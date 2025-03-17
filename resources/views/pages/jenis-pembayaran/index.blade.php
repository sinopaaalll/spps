@extends('layouts.app')

@section('title', 'Jenis Pembayaran')
@section('breadcrumb', 'Jenis Pembayaran')
@section('breadcrumb-text', 'Data Jenis Pembayaran')

@section('url')
    {{ route('jenis_pembayaran.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <a href="{{ route('jenis_pembayaran.create') }}" class="btn btn-primary">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="jenis-pembayaran-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>POS</th>
                                <th>Tahun Ajaran</th>
                                <th>Tipe</th>
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
            let table = $('#jenis-pembayaran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('jenis_pembayaran.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pos.nama',
                        name: 'pos'
                    },
                    {
                        data: null,
                        name: 'tahun ajaran',
                        render: function(data, type, row) {
                            return 'T.A ' +
                                row.tahun_ajaran.tahun_awal + '/' + row
                                .tahun_ajaran.tahun_akhir;
                        }
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function reloadTable() {
                table.ajax.reload();
            }

        });
    </script>
@endpush
