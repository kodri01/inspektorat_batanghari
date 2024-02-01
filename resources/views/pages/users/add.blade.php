@extends('layouts.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1><?= $judul ?></h1>
        </div>


        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- <h4>Basic DataTables</h4> -->
                            <a href="{{ route('users') }}" type="button"
                                class="btn btn-primary daterange-btn icon-left btn-icon">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Tambah Data -->
                            <form action="{{ route('users.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" autocomplete="off">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            name="username" autocomplete="off">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" autocomplete="off">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Wilayah Inspektur</label>
                                        <select id="inputState" class="form-control" name="wilayah">
                                            <option selected disabled>- Pilih Wilayah Inspektur -</option>
                                            @foreach ($wilayah as $list)
                                                <option value="{{ $list->id }}" class="text-capitalize">
                                                    {{ $list->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputState">Role</label>
                                        <select id="inputState" class="form-control" name="role">
                                            <option selected disabled>- Pilih Peran Pengguna -</option>
                                            @foreach ($role as $list)
                                                <option value="{{ $list->id }}" class="text-capitalize">
                                                    {{ $list->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="modal-footer bg-whitesmoke gap-3">
                                        <a href="{{ route('users') }}" type="button" class="btn btn-danger">Batal</a>
                                        <button class="btn btn-primary" name="tambahData">Simpan</button>
                                    </div>
                                </div>
                            </form>
                            <!-- penutup Tambah Data -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
