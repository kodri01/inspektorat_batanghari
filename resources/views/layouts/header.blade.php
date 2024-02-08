<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $title }}</title>
    <link rel="icon" href="{{ url('assets/logo.png') }}">

    <link rel="stylesheet" href="{{ url('fontawesome/css/all.min.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ url('css/styles.css') }}">
    <link rel="stylesheet" href="{{ url('css/components.css') }}">

    <link rel="stylesheet" href="{{ url('fontawesome/css/all.min.css') }}">

    <script src="{{ url('bootstrap-4/js/popper.min.js') }}"></script>
    <script src="{{ url('bootstrap-4/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('bootstrap-4/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ url('bootstrap-4/js/moment.min.js') }}"></script>
    <script src="{{ url('js/stisla.js') }}"></script>

    <script src="{{ url('js/scripts.js') }}"></script>
    <script src="{{ url('js/custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <style>
        /* Navbar */
        .form-inline {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }
    </style>

</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar justify-content-sm-between">
                <form class="form-inline mr-auto">

                    <ul class="navbar-nav mr-5">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                    </ul>

                    <h4 class="text-white mt-2"><strong class="text-uppercase">sistem informasi lhp inspektorat
                            daerah
                            batang hari</strong></h4>


                </form>
                <ul class="navbar-nav">
                    <li class="dropdown"><a href="" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ url('assets/avatar-1.png') }}" class="rounded-circle ">
                            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right w-25">
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="" class="text-uppercase"><img src="{{ url('assets/logo.png') }}" alt="LP"
                                width="30px">
                            lhp batang hari</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href=""><img src="{{ url('assets/logo.png') }}" alt="LP" width="47px"></a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Menu</li>
                        <li <?php if ($title == 'Dashboard LHP Batanghari') {
                            echo 'class="active"';
                        } ?>><a class="nav-link" href="{{ route('dashboard') }}"><i
                                    class="fas fa-fire"></i>
                                <span>Dashboard</span></a></li>

                        <li <?php if ($title == 'Data Temuan' || $title == 'Tambah Data Temuan' || $title == 'Edit Data Temuan' || $title == 'Rekomendasi Temuan' || $title == 'Tambah Rekomendasi Temuan') {
                            echo 'class="active"';
                        } ?>><a class="nav-link" href="{{ route('temuan') }}"><i
                                    class="fas fa-book-open"></i>
                                <span>Data Temuan</span></a></li>
                        <li <?php if ($title == 'Data Tindak Lanjut' || $title == 'Tambah Data Tindak Lanjut' || $title == 'Edit Data Tindak Lanjut' || $title == 'Proses Data Tindak Lanjut') {
                            echo 'class="active"';
                        } ?>><a class="nav-link" href="{{ route('tindakan') }}"><i
                                    class="fas fa-award"></i>
                                <span>Tindak Lanjut</span></a></li>

                        @role('Irban')
                            <li <?php if ($title == 'Upload LHP' || $title == 'Data LHP') {
                                echo 'class="active"';
                            } ?>><a class="nav-link" href="{{ route('lhp') }}"><i
                                        class="fas fa-file-upload"></i>
                                    <span>LHP</span></a></li>
                        @endrole
                        @role('superadmin')
                            <li <?php if ($title == 'Users LHP Batanghari' || $title == 'Tambah Data Users' || $title == 'Edit Data Users') {
                                echo 'class="active"';
                            } ?>><a class="nav-link" href="{{ route('users') }}"><i
                                        class="fas fa-user"></i>
                                    <span>Data Users</span></a></li>

                            <li <?php if ($title == 'Data Inspektur' || $title == 'Tambah Data Inspektur' || $title == 'Edit Data Inspektur' || $title == 'Data Obrik' || $title == 'Tambah Data Obrik' || $title == 'Edit Data Obrik') {
                                echo 'class="active"';
                            } ?>><a class="nav-link" data-bs-toggle="collapse" href="#collapseExample"
                                    role="button" aria-expanded="false" aria-controls="collapseExample"><i
                                        class="fas fa-database"></i>
                                    <span>Manajemen <i class="fas fa-chevron-down"
                                            style="font-size: 13px; margin-left:12px"></i></span></a>
                            </li>
                            <div class="collapse" style="margin-left: 20px" id="collapseExample">
                                <li <?php if ($title == 'Data Inspektur' || $title == 'Tambah Data Inspektur' || $title == 'Edit Data Inspektur') {
                                    echo 'class="active"';
                                } ?>><a class="nav-link" href="{{ route('inspektur') }}"><i
                                            class="fas fa-user-tie"></i>
                                        <span>Data Inspektur</span></a></li>
                                <li <?php if ($title == 'Data Obrik' || $title == 'Tambah Data Obrik' || $title == 'Edit Data Obrik') {
                                    echo 'class="active"';
                                } ?>><a class="nav-link" href="{{ route('obrik') }}"><i
                                            class="fas fa-book"></i>
                                        <span>Data Obrik</span></a></li>
                            </div>
                        @endrole

                        <li <?php if ($title == 'Laporan PHP' || $title == 'Laporan Rincian' || $title == 'Laporan Rekap' || $title == 'Laporan Rekapitulasi') {
                            echo 'class="active"';
                        } ?>><a class="nav-link" data-bs-toggle="collapse" href="#collapseExample1"
                                role="button" aria-expanded="false" aria-controls="collapseExample1"><i
                                    class="fas fa-file"></i>
                                <span>Laporan <i class="fas fa-chevron-down"
                                        style="font-size: 13px; margin-left:12px"></i></span></a>
                        </li>
                        <div class="collapse" style="margin-left: 20px" id="collapseExample1">
                            @role('Irban')
                                <li <?php if ($title == 'Laporan PHP') {
                                    echo 'class="active"';
                                } ?>><a class="nav-link" href="{{ route('laporanPHP') }}"><i
                                            class="fas fa-clipboard-list"></i>
                                        <span>Laporan PHP</span></a></li>
                            @endrole

                            <li <?php if ($title == 'Laporan Rincian') {
                                echo 'class="active"';
                            } ?>><a class="nav-link" href="{{ route('laporan_rincian') }}"><i
                                        class="fas fa-clipboard-list"></i>
                                    <span>Laporan Rincian</span></a></li>
                            <li <?php if ($title == 'Laporan Rekap') {
                                echo 'class="active"';
                            } ?>><a class="nav-link" href="{{ route('laporan_rekap') }}"><i
                                        class="fas fa-clipboard-list"></i>
                                    <span>Laporan Rekap LHP</span></a></li>
                            <li <?php if ($title == 'Laporan Rekapitulasi') {
                                echo 'class="active"';
                            } ?>><a class="nav-link"
                                    href="{{ route('laporan_rekapitulasi') }}"><i class="fas fa-clipboard-list"></i>
                                    <span>Laporan Rekapitulasi</span></a></li>
                        </div>
                    </ul>
                </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content">
