@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('breadcrumb', 'Siswa')
@section('breadcrumb-text', 'Detail Siswa')

@section('url')
    {{ route('siswa.index') }}
@endsection

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="d-block m-t-5">Siswa</h5>
                    <div class="card-tools text-end">
                        <a href="{{ route('siswa.index') }}">
                            <button type="button" class="btn btn-primary">
                                <span class="fa fa-arrow-left"></span>&nbsp; Back
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nama</p>
                                    <p class="mb-0">{{ $siswa->nama }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Jenis Kelamin</p>
                                    <p class="mb-0">{{ $siswa->jk }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Tempat Lahir</p>
                                    <p class="mb-0">{{ $siswa->tempat_lahir }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Tanggal Lahir</p>
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 pb-0">
                            <p class="mb-1 text-muted">Alamat</p>
                            <p class="mb-0">{{ $siswa->alamat }}</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="d-block m-t-5">Sekolah</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">NIS</p>
                                    <p class="mb-0">{{ $siswa->nis }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Kelas</p>
                                    <p class="mb-0">{{ $siswa->kelas->nama_kelas }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 pb-0">
                            <p class="mb-1 text-muted">Status</p>
                            <p class="mb-0">{{ $siswa->status }}</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="d-block m-t-5">Keluarga</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nama Ibu</p>
                                    <p class="mb-0">{{ $siswa->nama_ibu }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nama Ayah</p>
                                    <p class="mb-0">{{ $siswa->nama_ayah }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Nama Wali</p>
                                    <p class="mb-0">{{ $siswa->nama_wali }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">Telepon Orang Tua</p>
                                    <p class="mb-0">
                                        {{ $siswa->telp_ortu }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection



@push('custom-scripts')
@endpush
