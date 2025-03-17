@extends('layouts.app')

@section('title', 'Kelas')
@section('breadcrumb', 'Kelas')
@section('breadcrumb-text', 'Data Kelas')

@section('url')
    {{ route('kelas.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Data @yield('title')</h5>
                    <div class="card-tools text-end">
                        <button type="button" class="btn btn-primary" id="createKelasBtn">
                            <span class="fa fa-plus"></span>&nbsp; Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="kelas-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
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

    <div id="kelasFormModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="kelasFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kelasFormModalLabel">Form Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kelasForm" autocomplete="off"> <!-- Tambahkan form -->
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="nama_kelas" class="col-lg-3 col-form-label text-lg-end">Nama Kelas *</label>
                            <div class="col-lg-9">
                                <input type="text" id="nama_kelas" name="nama_kelas" class="form-control"
                                    placeholder="Masukkan Nama Kelas">
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
            let table = $('#kelas-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kelas.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas'
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
            $('#createKelasBtn').on('click', function() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $('#kelasForm')[0].reset();
                $('#update_id').val('');
                $('#kelasFormModalLabel').text('Tambah Kelas');
                $('#save').text('Simpan');
                $('#kelasFormModal').modal('show');
            });

            // Simpan atau Update Data
            $('#save').on('click', function(e) {
                e.preventDefault();
                let id = $('#update_id').val();
                let url = id ? '{{ url('kelas') }}/' + id : '{{ route('kelas.store') }}';
                let type = id ? 'PUT' : 'POST';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: type,
                    data: {
                        nama_kelas: $('#nama_kelas').val()
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Success');
                        $('#kelasFormModal').modal('hide');
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
            $(document).on('click', '.edit-kelas', function() {
                let id = $(this).data('id');
                let url = $(this).data('url');

                $.get(url, function(response) {
                    // Hapus semua error validation sebelumnya
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $('#update_id').val(response.kelas.id);
                    $('#nama_kelas').val(response.kelas.nama_kelas);
                    $('#kelasFormModalLabel').text('Edit Kelas');
                    $('#save').text('Ubah');
                    $('#kelasFormModal').modal('show');
                });
            });


        });
    </script>
@endpush
