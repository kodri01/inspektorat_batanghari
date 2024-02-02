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

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-database my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Obrik</h4>
                        </div>
                        <div class="card-body">
                            <h2> {{ $obrik }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-book-open my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Temuan</h4>
                        </div>

                        <div class="card-body">
                            <h2> {{ $temuan }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-award my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Tindak Lanjut</h4>
                        </div>
                        <div class="card-body">
                            @if ($belum != null)
                                <h2> {{ $belum->belum }} </h2>
                            @else
                                <h2> 0 </h2>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-award my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tindak Lanjut Selesai</h4>
                        </div>
                        <div class="card-body">
                            <h2> {{ $selesai }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-award my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tindak Lanjut Dalam Proses</h4>
                        </div>
                        <div class="card-body">
                            <h2> {{ $dalamProses }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-award my-4"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tindak Lanjut Belum Diproses</h4>
                        </div>
                        <div class="card-body">
                            <h2> {{ $belum->belum }} </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- hmm -->

        </div>
    </section>
@endsection
