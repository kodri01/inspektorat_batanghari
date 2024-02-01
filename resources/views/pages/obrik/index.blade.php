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



        <div class="card">
            <div class="card-body">
                <a href="{{ route('obrik.add') }}" class="btn btn-primary mb-4">+ Data Obrik</a>
                {{ $dataTable->table() }}
            </div>

        </div>

    </section>

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    @endpush
@endSection;
