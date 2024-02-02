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
            <nav style="--bs-breadcrumb-divider: '>';" class="p-4" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('temuan') }}" class="text-warning">Temuan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rekomendasi Temuan</li>
                </ol>
            </nav>
            <div class="card-body">
                <a href="{{ route('rekomendasi.add', $rekomen->id) }}" class="btn btn-primary mb-4">+
                    Rekomendasi</a>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Rekomendasi</th>
                            <th scope="col">Nilai Rekomendasi Tercatat</th>
                            <th scope="col">Penanggung Jawab</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekomendasi as $r)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $r->rekomendasi }}</td>
                                <td>{{ 'Rp ' . number_format($r->nilai_rekomendasi, 0, ',', '.') }}</td>
                                @if ($r->penanggung->name == null)
                                    <td>{{ $r->obrik->name }}</td>
                                @else
                                    <td>{{ $r->penanggung->name }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td><b>Total :</b></td>
                            <td><b>{{ 'Rp ' . number_format($nilaiRekom->total_rekomendasi, 0, ',', '.') }}</b>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </section>
@endSection;
