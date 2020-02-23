<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEO::generate() !!}
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ Theme::url('css/pages/login.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ Theme::url('css/main.css') }}">
    @routes
</head>
<body class="hold-transition login-page">
<div class="login-box">
    @php $logo = get_logo('/images/logo.svg'); @endphp
    <div class="login-logo">
        @if($logo)
            <a href="{{ route('admin') }}"><img src="{{ $logo }}" alt="Logo" style="max-width: 320px"></a>
        @else
            <a href="{{ route('admin') }}"><b>Webvi</b>CMS</a>
        @endif
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('admin::login.ADMINISTRATION') }}</p>

            <form action="{{ route('admin.login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @if($errors->has('email')) is-invalid @endif"
                           placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @if($errors->has('password')) is-invalid @endif"
                           placeholder="{{ __('admin::login.Password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" value="1" id="remember" name="remember">
                            <label for="remember">
                                {{ __('admin::login.Remember Me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-5">
                        <button type="submit"
                                class="btn btn-primary btn-block">{{ __('admin::login.Sign In') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            @if(env('FACEBOOK_CLIENT_ID') || env('GOOGLE_CLIENT_ID'))
                <div class="social-auth-links text-center mb-3">
                    <p>- {{ __('admin::login.OR') }} -</p>
                    @if(env('FACEBOOK_CLIENT_ID'))
                        <a href="{{ route('login.facebook') }}" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> {{ __("admin::login.Sign in using Facebook") }}
                        </a>
                    @endif
                    @if(env('GOOGLE_CLIENT_ID'))
                        <a href="{{ route('login.google') }}" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> {{ __('admin::login.Sign in using Google') }}
                        </a>
                    @endif
                </div>
                <!-- /.social-auth-links -->
            @endif
            <p class="mb-1">
                <a href="{{ route('password.request') }}">{{ __('admin::login.I forgot my password') }}</a>
            </p>
            <p class="mb-0">
                <a href="{{ route('register') }}"
                   class="text-center">{{ __('admin::login.Register a new membership') }}</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<!-- AdminLTE App -->
<script src="{{ \Theme::url('js/main.js') }}"></script>

</body>
</html>

