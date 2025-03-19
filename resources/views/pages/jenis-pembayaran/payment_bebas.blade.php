@extends('layouts.app')

@section('title', 'Tambah Tarif Pembayaran')
@section('breadcrumb', 'Tarif Pembayaran')
@section('breadcrumb-text', 'Tambah Tarif Pembayaran')

@section('url')
    {{ route('jenis_pembayaran.index') }}
@endsection

@push('styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="form-group row">
                        <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Jenis Bayar</label>
                        <div class="col-lg-6">
                            <input type="text" name="" class="form-control" readonly
                                value="{{ $jenis_pembayaran->pos->nama . ' T.A ' . $jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $jenis_pembayaran->tahun_ajaran->tahun_akhir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tahun Ajaran</label>
                        <div class="col-lg-6">
                            <input type="text" name="" class="form-control" readonly
                                value="{{ $jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $jenis_pembayaran->tahun_ajaran->tahun_akhir }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tipe</label>
                        <div class="col-lg-6">
                            <input type="text" name="" class="form-control" readonly
                                value="{{ $jenis_pembayaran->tipe }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bebas') }}"
                        method="post">
                        @csrf

                        <div class="form-group row">
                            <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Kelas*</label>
                            <div class="col-lg-6">
                                <select name="kelas" id="kelas"
                                    class="form-control @error('kelas') is-invalid @enderror">
                                    <option value="" selected disabled>Pilih Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                @error('kelas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tarif</label>
                            <div class="col-lg-6">
                                <input type="text" name="bill"
                                    class="form-control @error('bill') is-invalid @enderror">
                                @error('bill')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pos_id" class="col-lg-4 col-form-label text-lg-end"></label>
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;
                                    Simpan</button>
                                <a href="{{ url('jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bebas') }}"
                                    class="btn btn-default">Cancel</a>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('custom-scripts')
    <script src="{{ asset('assets/js/plugins/imask.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var billMask = IMask(document.querySelector('input[name="bill"]'), {
                mask: 'Rp. num',
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: ','
                    }
                }
            });
        });
    </script>
@endpush
