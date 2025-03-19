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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group row">
                                <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Jenis Bayar</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $jenis_pembayaran->pos->nama . ' T.A ' . $jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $jenis_pembayaran->tahun_ajaran->tahun_akhir }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tahun Ajaran</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $jenis_pembayaran->tahun_ajaran->tahun_awal . '/' . $jenis_pembayaran->tahun_ajaran->tahun_akhir }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tipe</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $jenis_pembayaran->tipe }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kelas_id" class="col-lg-4 col-form-label text-lg-end">Kelas*</label>
                                <div class="col-lg-6">
                                    <select id="kelas_id" class="form-control @error('kelas') is-invalid @enderror">
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tarif setiap bulan sama</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="bill" class="col-lg-4 col-form-label text-lg-end">Tarif Bulanan
                                    (Rp.)</label>
                                <div class="col-lg-6">
                                    <input type="text" id="bill" class="form-control"
                                        placeholder="Masukan nilai lalu tekan enter">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5>Tarif setiap bulan tidak sama</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('/jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bulanan') }}"
                        method="post">
                        @csrf

                        <input type="hidden" name="kelas" id="kelas">

                        @foreach ($bulan as $b)
                            <div class="form-group row">
                                <label for="bill_{{ $b->id }}"
                                    class="col-lg-2 col-form-label text-lg-end">{{ $b->nama }}</label>
                                <div class="col-lg-10">
                                    <input type="text" name="bill[{{ $b->id }}]" id="bill_{{ $b->id }}"
                                        class="form-control @error('bill.' . $b->id) is-invalid @enderror">
                                    @error('bill.' . $b->id)
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach


                        <div class="form-group row">
                            <label for="pos_id" class="col-lg-2 col-form-label text-lg-end"></label>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span>&nbsp;
                                    Simpan</button>
                                <a href="{{ url('jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bulanan') }}"
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
            var billMask = IMask(document.querySelector('#bill'), {
                mask: 'Rp. num',
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: ','
                    }
                }
            });

            document.querySelectorAll('input[name^="bill["]').forEach(function(input) {
                IMask(input, {
                    mask: 'Rp. num',
                    blocks: {
                        num: {
                            mask: Number,
                            thousandsSeparator: ','
                        }
                    }
                });
            });

            $('#kelas_id').on('change', function() {
                let id = $(this).val();
                $('input[name="kelas').val(id);
            });

            $('#bill').keypress(function(event) {
                if (event.which === 13) {
                    event.preventDefault();

                    let nilai = $(this).val();
                    $('input[name^="bill["]').val(nilai);
                }
            });
        });
    </script>
@endpush
