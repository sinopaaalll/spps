@extends('layouts.app')

@section('title', 'Bulan')
@section('breadcrumb', 'Bulan')
@section('breadcrumb-text', 'Data Bulan')

@section('url')
    {{ route('bulan.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <button type="button" class="btn btn-primary" id="createBulanBtn">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="bulan-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bulan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>

    <div id="bulanFormModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bulanFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulanFormModalLabel">Form bulan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulanForm" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="nama" class="col-lg-3 col-form-label text-lg-end">Bulan *</label>
                            <div class="col-lg-9">
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Masukkan bulan">
                            </div>
                        </div>
                        <input type="hidden" id="update_id" name="update_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="save">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
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
            let table = $('#bulan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('bulan.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
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

            // Tambah Data (Open Modal)
            $('#createBulanBtn').on('click', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('#bulanForm')[0].reset();
                $('#update_id').val('');
                $('#bulanFormModalLabel').text('Tambah Bulan');
                $('#save').text('Simpan');
                $('#bulanFormModal').modal('show');
            });

            // Simpan atau Update Data
            $('#save').on('click', function(e) {
                e.preventDefault();
                let id = $('#update_id').val();
                let url = id ? '{{ url('bulan') }}/' + id : '{{ route('bulan.store') }}';
                let type = id ? 'PUT' : 'POST';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: type,
                    data: {
                        nama: $('#nama').val()
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Success');
                        $('#bulanFormModal').modal('hide');
                        reloadTable();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        $.each(errors, function(key, value) {
                            let input = $('#' + key);
                            input.addClass('is-invalid').after(
                                '<div class="invalid-feedback">' + value[0] +
                                '</div>');
                        });
                    }
                });
            });

            // Edit Data
            $(document).on('click', '.edit-bulan', function() {
                let id = $(this).data('id');
                let url = $(this).data('url');

                $.get(url, function(response) {
                    // Hapus semua error validation sebelumnya
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $('#update_id').val(response.bulan.id);
                    $('#nama').val(response.bulan.nama);
                    $('#bulanFormModalLabel').text('Edit bulan');
                    $('#save').text('Ubah');
                    $('#bulanFormModal').modal('show');
                });
            });


        });
    </script>
@endpush
