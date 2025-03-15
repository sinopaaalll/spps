@extends('layouts.app')

@section('title', 'Tahun Pelajaran')
@section('breadcrumb', 'Tahun Pelajaran')
@section('breadcrumb-text', 'Data Tahun Pelajaran')

@section('url')
    {{ route('tahun_pelajaran.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <a href="{{ route('tahun_pelajaran.create') }}" class="btn btn-primary">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tahun-pelajaran-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun Pelajaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        {{-- DataTables Server side --}}
                        <tbody></tbody>
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
            let table = $('#tahun-pelajaran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tahun_pelajaran.index') }}',
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Nomor urut dari 1
                        }
                    },
                    {
                        data: null,
                        name: 'tahun_awal',
                        render: function(data, type, row) {
                            return row.tahun_awal + '/' + row.tahun_akhir;
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            if (data === "Aktif") {
                                return '<span class="badge bg-success">' + row.status +
                                    '</span>';
                            } else {
                                return '<span class="badge bg-danger">' + row.status +
                                    '</span>';
                            }
                        }
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
