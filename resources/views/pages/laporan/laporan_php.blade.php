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
                {{-- <form action="{{ route('excelPHP') }}" method="get">
                    <div class="row w-75">
                        <div class="col-sm-3">
                            <div class="input-group flex-nowrap my-3">
                                <span class="input-group-text" id="addon-wrapping"><b>Tahun:</b></span>
                                <select id="tahun" class="form-control">
                                    <option value="" disabled selected>Tahun</option>
                                    @foreach ($tahun as $th)
                                        <option value="{{ $th }}">{{ $th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group flex-nowrap my-3">
                                <span class="input-group-text" id="addon-wrapping"><b>Obrik:</b></span>
                                <select id="obrik" class="form-control">
                                    <option value="" selected disabled>Obrik</option>
                                    @foreach ($obrik as $th)
                                        <option value="{{ $th }}">{{ $th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group flex-nowrap my-3">
                                <span class="input-group-text" id="addon-wrapping"><b>File:</b></span>
                                <select id="file" class="form-control">
                                    <option value="" selected disabled>File</option>
                                    <option value="Excel">Excel</option>
                                    <option value="PDF">PDF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group flex-nowrap mt-4">
                                <button type="submit" class="btn btn-sm btn-success">Download</button>
                            </div>
                        </div>
                    </div>
                </form> --}}
                {{ $dataTable->table() }}
            </div>

        </div>

    </section>

    @push('scripts')
        {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
        <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
        <script src="/vendor/datatables/buttons.server-side.js"></script>
    @endpush
@endSection;
