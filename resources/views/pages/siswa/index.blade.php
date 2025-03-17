@extends('layouts.app')

@section('title', 'Siswa')
@section('breadcrumb', 'Siswa')
@section('breadcrumb-text', 'Data Siswa')

@section('url')
    {{ route('siswa.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filter</h5>
                    <form id="filter-form">
                        <div class="row">
                            <!-- Input NIS -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="number" id="nis" name="nis" class="form-control"
                                        placeholder="Masukkan NIS...">
                                </div>
                            </div>

                            <!-- Input Nama -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="Masukkan Nama...">
                                </div>
                            </div>

                            <!-- Select Kelas -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="kelas_id" name="kelas_id" class="form-control">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </a>
                        <a href="{{ route('siswa.import') }}" class="btn btn-success">
                            <span class="fa fa-upload"></span>&nbsp; Import
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="siswa-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Status</th>
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
            let table = $('#siswa-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('siswa.index') }}",
                    data: function(d) {
                        d.nis = $('#nis').val();
                        d.nama = $('#nama').val();
                        d.kelas_id = $('#kelas_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'nis',
                        name: 'nis'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'kelas.nama_kelas'
                    },
                    {
                        data: 'status',
                        render: function(data) {
                            return data === "Aktif" ?
                                '<span class="badge bg-success">' + data + '</span>' :
                                '<span class="badge bg-danger">' + data + '</span>';
                        }
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            $('#nis, #nama').on('keyup', function() {
                table.ajax.reload();
            });

            // Event ketika user memilih kelas (change)
            $('#kelas_id').on('change', function() {
                table.ajax.reload();
            });

        });
    </script>
@endpush
