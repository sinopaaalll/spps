@extends('layouts.app')

@section('title', 'Edit Jenis Pembayaran')
@section('breadcrumb', 'Jenis Pembayaran')
@section('breadcrumb-text', 'Edit Jenis Pembayaran')

@section('url')
    {{ route('jenis_pembayaran.index') }}
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
                        <a href="{{ route('jenis_pembayaran.index') }}">
                            <button type="button" class="btn btn-primary">
                                <span class="fa fa-arrow-left"></span>&nbsp; Back
                            </button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis_pembayaran.update', $jenis_pembayaran->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Pos*</label>
                            <div class="col-lg-6">
                                <select name="pos_id" id="pos_id"
                                    class="form-control @error('pos_id') is-invalid @enderror">
                                    <option value="" disabled>Pilih Pos</option>
                                    @foreach ($pos as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $p->id == $jenis_pembayaran->pos_id ? 'selected' : '' }}>{{ $p->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pos_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tahun_ajaran_id" class="col-lg-4 col-form-label text-lg-end">Tahun Ajaran*</label>
                            <div class="col-lg-6">
                                <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                                    class="form-control @error('tahun_ajaran_id') is-invalid @enderror">
                                    <option value="" disabled>Pilih tahun ajaran</option>
                                    @foreach ($tahun_ajaran as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $t->id == $jenis_pembayaran->tahun_ajaran_id ? 'selected' : '' }}>
                                            {{ $t->tahun_awal . '/' . $t->tahun_akhir }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun_ajaran_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="status" class="col-lg-4 col-form-label text-lg-end">Tipe*</label>
                            <div class="col-lg-6">
                                @foreach ($tipe as $key => $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipe"
                                            value="{{ $item }}" id="tipe{{ $loop->index }}"
                                            {{ $item == $jenis_pembayaran->tipe ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tipe{{ $loop->index }}">
                                            {{ $item }} </label>
                                    </div>
                                @endforeach
                                @error('tipe')
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
