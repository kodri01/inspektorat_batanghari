@include('Auth.components.header')
<!--================= Main Wrapper ==================-->
<div class="wrapper">

    <div class="login-form form-input-login">
        <form action="{{ route('tologin') }}" method="post">
            <div class="login-form-title">
                <h5 class="text-uppercase">selamat datang di aplikasi</h5>
                <h3 class="text-uppercase"><strong>sistem informasi hasil pemeriksaan</strong></h3>
                <h5 class="text-uppercase">inspektorat daerah kabupaten batang hari</h5>
                <img src="{{ url('assets/logo.png') }}" alt="" srcset="" class="mt-2"
                    style="width: 150px; height: auto;">
            </div>
            @csrf
            <div class="card-body">
                @if (session('errors'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Something it's wrong:
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                <div class="form__group">
                    <input type="text" class="form__field" placeholder="Username" name="username" required />
                    <label for="username" class="form__label">Username</label>
                </div>
                <div class="form__group">
                    <input type="password" class="form__field" placeholder="Password" name="password" required />
                    <label for="password" class="form__label">Password</label>
                </div>
            </div>
            <div class="card-footer cards-down mt-3">
                <button type="submit" class="btn btn-primary custom-btn">Log In</button>
            </div>
        </form>
    </div>

</div>
@include('Auth.components.footer')
