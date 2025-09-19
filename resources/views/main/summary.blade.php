@extends('layouts.app')

@section('title', "Summary Anggaran Triwulan $triwulan")

@section('content')
<div class="container-flex">
    <h3>Summary Anggaran Triwulan {{ $triwulan }}</h3>
    <p class="text-muted">Ringkasan data anggaran dari Google Sheets</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Total Anggaran</h6>
                <h4>Rp {{ number_format($total_anggaran, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Total Realisasi</h6>
                <h4>Rp {{ number_format($total_realisasi, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
</div>
@endsection
