@extends('layouts.app')

@section('title', 'Detail Tarif Pembayaran')
@section('breadcrumb', 'Tarif Pembayaran')
@section('breadcrumb-text', 'Detail Tarif Pembayaran')

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
                                <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Kelas</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" readonly
                                        value="{{ $siswa->kelas->nama_kelas }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pos_id" class="col-lg-4 col-form-label text-lg-end">Tipe</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" readonly value="{{ $siswa->nama }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">



                    @foreach ($bulanan as $b)
                        <div class="form-group row">
                            <label for="bill_{{ $b->bulan->id }}"
                                class="col-lg-2 col-form-label text-lg-end">{{ $b->bulan->nama }}</label>
                            <div class="col-lg-10">
                                <input type="text" name="bill[{{ $b->bulan->id }}]" id="bill_{{ $b->bulan->id }}"
                                    class="form-control" value="{{ $b->bill }}" readonly>
                            </div>
                        </div>
                    @endforeach


                    <div class="form-group row">
                        <label for="pos_id" class="col-lg-2 col-form-label text-lg-end"></label>
                        <div class="col-lg-10">
                            <a href="{{ url('jenis_pembayaran/' . $jenis_pembayaran->id . '/payment_bulanan') }}"
                                class="btn btn-primary"><span class="fa fa-arrow-left"></span>&nbsp; Kembali</a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection



@push('custom-scripts')
    <script src="{{ asset('assets/js/plugins/imask.min.js') }}"></script>
    <script>
        $(document).ready(function() {

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
