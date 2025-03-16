@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('breadcrumb', 'Siswa')
@section('breadcrumb-text', 'Edit Siswa')

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
                    <span class="d-block m-t-5">*) Kolom wajib diisi.</span>
                    <div class="card-tools text-end">
                        <a href="{{ route('siswa.index') }}">
                            <button type="button" class="btn btn-primary">
                                <span class="fa fa-arrow-left"></span>&nbsp; Back
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('siswa.update', $siswa->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3">A. Siswa:</h5>
                        <div class="form-group row">
                            <label for="nama" class="col-lg-4 col-form-label text-lg-end">Nama*</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ $siswa->nama }}"
                                    placeholder="Input nama lengkap...">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="jk" class="col-lg-4 col-form-label text-lg-end">Jenis Kelamin*</label>
                            <div class="col-lg-6">
                                @foreach ($jk as $key => $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jk"
                                            value="{{ $item }}" id="jk{{ $loop->index }}"
                                            {{ $item == $siswa->jk ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jk{{ $loop->index }}">
                                            {{ $item }} </label>
                                    </div>
                                @endforeach
                                @error('jk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tempat_lahir" class="col-lg-4 col-form-label text-lg-end">Tempat Lahir</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir" value="{{ $siswa->tempat_lahir }}"
                                    placeholder="Input tempat lahir...">
                                @error('tempat_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_lahir" class="col-lg-4 col-form-label text-lg-end">Tanggal Lahir</label>
                            <div class="col-lg-6">
                                <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ $siswa->tanggal_lahir }}"
                                    placeholder="Input tanggal lahir...">
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-lg-4 col-form-label text-lg-end">Alamat</label>
                            <div class="col-lg-6">
                                <textarea name="alamat" id="alamat" class="form-control">{{ $siswa->alamat }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">B. Sekolah:</h5>
                        <div class="form-group row">
                            <label for="nis" class="col-lg-4 col-form-label text-lg-end">NIS*</label>
                            <div class="col-lg-6">
                                <input type="number" class="form-control @error('nis') is-invalid @enderror" id="nis"
                                    name="nis" value="{{ $siswa->nis }}" placeholder="Input nomor induk siswa...">
                                @error('nis')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kelas_id" class="col-lg-4 col-form-label text-lg-end">Kelas*</label>
                            <div class="col-lg-6">
                                <select name="kelas_id" id="kelas_id" class="form-control">
                                    <option value="">Pilih kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ $k->id == $siswa->kelas_id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="status" class="col-lg-4 col-form-label text-lg-end">Status*</label>
                            <div class="col-lg-6">
                                @foreach ($status as $key => $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="{{ $item }}" id="status{{ $loop->index }}"
                                            {{ $item == $siswa->status ? 'checked' : '' }}>
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

                        <hr class="my-4">
                        <h5 class="mb-3">C. Keluarga:</h5>
                        <div class="form-group row">
                            <label for="nama_ibu" class="col-lg-4 col-form-label text-lg-end">Nama Ibu</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                    id="nama_ibu" name="nama_ibu" value="{{ $siswa->nama_ibu }}"
                                    placeholder="Input nama ibu...">
                                @error('nama_ibu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_ayah" class="col-lg-4 col-form-label text-lg-end">Nama Ayah</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                    id="nama_ayah" name="nama_ayah" value="{{ $siswa->nama_ayah }}"
                                    placeholder="Input nama ayah...">
                                @error('nama_ayah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_wali" class="col-lg-4 col-form-label text-lg-end">Nama Wali</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                    id="nama_wali" name="nama_wali" value="{{ $siswa->nama_wali }}"
                                    placeholder="Input nama wali...">
                                @error('nama_wali')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telp_ortu" class="col-lg-4 col-form-label text-lg-end">Telepon Orang Tua</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control @error('telp_ortu') ? is-invalid @enderror"
                                    id="telp_ortu" name="telp_ortu" value="{{ $siswa->telp_ortu }}"
                                    placeholder="Input nomor telepon...">
                                @error('telp_ortu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label text-lg-end"></label>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;
                                    Ubah</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('custom-scripts')
@endpush
