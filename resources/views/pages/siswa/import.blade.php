@extends('layouts.app')

@section('title', 'Import Data Siswa')
@section('breadcrumb', 'Siswa')
@section('breadcrumb-text', 'Import Data Siswa')

@section('url')
    {{ route('siswa.index') }}
@endsection

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5>Petunjuk Singkat</h1>
                        <p>Penginputan data Siswa bisa dilakukan dengan mengupload file Ms. Excel dengan format
                            (.xlsx), Format file excel harus sesuai kebutuhan aplikasi. Silahkan unduh formatnya <a
                                href="{{ route('siswa.template') }}"><span class="badge bg-success">Disini</span></a>
                        </p>

                        <br>
                        <h6>CATATAN :</h6>
                        <ol>
                            <li>
                                <p>Heading pada excel dengan <b>KOMENTAR</b> menandakan kolom <b>WAJIB</b> di isi.</p>
                            </li>
                            <li>
                                <p>Pengisian data <b>NAMA, TEMPAT LAHIR</b> diawali dengan huruf kapital.</p>
                            </li>
                            <li>
                                <p>Pengisian data <b>JENIS KELAMIN</b> berupa dua opsi yaitu <b>Laki-laki / Perempuan</b>
                                </p>
                            </li>
                            <li>
                                <p>Pengisian data <b>STATUS</b> berupa dua opsi yaitu <b>Aktif / Tidak Aktif</b></p>
                            </li>
                            <li>
                                <p>Pengisian data <b>KELAS</b> berupa opsi yaitu dari <b>1 s/d 6</b></p>
                            </li>
                            <li>
                                <p>Pengisian jenis data <b>TANGGAL</b> diisi dengan format <b>YYYY-MM-DD</b> Contoh
                                    <b>2017-12-21</b>
                                </p>
                            </li>
                            <li>
                                <p>Pengisian data <b>TELEPON ORANG TUA</b> harus berupa angka Contoh
                                    <b>6289624626152</b>
                                </p>
                            </li>
                        </ol>

                        <form action="{{ route('siswa.import.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file" class="form-label">File</label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                    id="file" name="file">
                                @error('file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label"></label>
                                <button type="submit" class="btn btn-success"><span class="fa fa-save"></span>&nbsp;
                                    Import</button>
                                <a href="{{ route('siswa.index') }}">
                                    <button type="button" class="btn btn-primary">
                                        <span class="fa fa-arrow-left"></span>&nbsp; Back
                                    </button>
                                </a>
                            </div>

                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('custom-scripts')
@endpush
