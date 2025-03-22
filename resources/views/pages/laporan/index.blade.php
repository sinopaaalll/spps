@extends('layouts.app')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')
@section('breadcrumb-text', 'Data laporan')

@section('url')
    {{ route('laporan.index') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('laporan.export') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="" class="form-label">Tanggal Awal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="date" name="tgl_awal"
                                            class="form-control @error('tgl_awal') is-invalid @enderror"
                                            value="{{ old('tgl_awal') }}">
                                        @error('tgl_awal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="" class="form-label">Tanggal Akhir</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="date" name="tgl_akhir"
                                                class="form-control @error('tgl_akhir') is-invalid @enderror"
                                                value="{{ old('tgl_akhir') }}">
                                            @error('tgl_akhir')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-success mt-4">
                                        <span class="fa fa-file-excel"></span>&nbsp; Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>





@endsection

@push('styles')
@endpush

@push('custom-scripts')
@endpush
