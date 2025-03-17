@extends('layouts.app')

@section('title', 'Tahun Ajaran')
@section('breadcrumb', 'Tahun Ajaran')
@section('breadcrumb-text', 'Data Tahun Ajaran')

@section('url')
    {{ route('tahun_ajaran.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <a href="{{ route('tahun_ajaran.create') }}" class="btn btn-primary">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="tahun-ajaran-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tahun Ajaran</th>
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
            let table = $('#tahun-ajaran-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tahun_ajaran.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
