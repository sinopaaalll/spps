@extends('layouts.app')

@section('title', 'Tambah Tahun Pelajaran')
@section('breadcrumb', 'Tahun Pelajaran')
@section('breadcrumb-text', 'Tambah Tahun Pelajaran')

@section('url')
    {{ route('tahun_pelajaran.index') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="d-block m-t-5">*) Kolom wajib diisi.</span>
                    <div class="card-tools text-end">
                        <a href="{{ route('tahun_pelajaran.index') }}">
                            <button type="button" class="btn btn-primary">
                                <span class="fa fa-arrow-left"></span>&nbsp; Back
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('tahun_pelajaran.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun_awal" class="form-label">Tahun Awal *</label>
                                    <input type="text" class="form-control @error('tahun_awal') is-invalid @enderror"
                                        id="tahun_awal" name="tahun_awal" value="{{ old('tahun_awal') }}"
                                        placeholder="Pilih tahun awal">
                                    @error('tahun_awal')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun_akhir" class="form-label">Tahun Akhir *</label>
                                    <input type="text" class="form-control @error('tahun_akhir') is-invalid @enderror"
                                        id="tahun_akhir" name="tahun_akhir" value="{{ old('tahun_akhir') }}" readonly>
                                    @error('tahun_akhir')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun_akhir" class="form-label">Status *</label>
                                </div>
                                <div class="form-group">
                                    @foreach ($status as $key => $item)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status"
                                                value="{{ $item }}" id="status{{ $loop->index }}"
                                                {{ $item == 'Tidak Aktif' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status{{ $loop->index }}">
                                                {{ $item }} </label>
                                        </div>
                                    @endforeach

                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label"></label>
                            <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush

@push('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tahun_awal').datepicker({
                format: 'yyyy', // Format hanya tahun
                viewMode: 'years', // Mode tampilan tahun
                minViewMode: 'years', // Hanya bisa memilih tahun
                autoclose: true // Menutup otomatis setelah memilih
            });

            $(document).on('change', '#tahun_awal', function() {
                let tahun_awal = parseInt($('#tahun_awal').val());
                let tahun_akhir = tahun_awal + 1;
                $('#tahun_akhir').val(tahun_akhir);
            });
        });
    </script>
@endpush
