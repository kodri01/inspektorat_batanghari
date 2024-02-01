@extends('layouts.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $judul }}</h1>
        </div>
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif



        <div class="card table-responsive">
            <div class="card-body">
                <a href="{{ route('tindakan.add') }}" class="btn btn-primary mb-4">+ Data Tindak Lanjut</a>
                {{ $dataTable->table() }}
                @role('Irban')
                    <label class=" text-danger mt-2 text-uppercase"><strong>Keterangan :</strong></label><br>
                    <li class="text-capitalize">Data yang sudah <strong><u>terkirim</u></strong> tidak bisa diedit lagi</li>
                @endrole
            </div>

        </div>
    </section>


    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
@endSection;
