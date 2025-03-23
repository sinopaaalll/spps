@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb-text', 'Dashboard')

@section('url')
    {{ route('dashboard') }}
@endsection

@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-1">{{ $totalPenerimaanHariIni }}</h3>
                            <p class="text-muted mb-0">Penerimaan Hari ini</p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="ti ti-currency-dollar text-success f-36"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-1">{{ $totalPenerimaan }}</h3>
                            <p class="text-muted mb-0">Total Penerimaan</p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="ti ti-report-money text-primary f-36"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-1">{{ $totalSiswaAktif }}</h3>
                            <p class="text-muted mb-0">Siswa Aktif</p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="ti ti-users text-warning f-36"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
