@extends($themeName.'::layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center mg-t-106 mg-b-200">
        <div class="col-md-6">
            <div class="login">
                <div class="login__title">
                    {{ __('vcch::login.Welcome Back') }}!
                    <p>
                        {!! trans('vcch::login.description') !!}
                    </p>
                </div>
                <div class="login__body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group @if($errors->has('email')) login__error @endif">
                            {{ Form::text('email', old('email'), ['placeholder' => __('Email'), 'class' => 'form-control login__input']) }}
                            {{ Form::error('email', $errors) }}
                        </div>
                        <div class="form-group @if($errors->has('password')) login__error @endif">
                            {{ Form::password('password', ['placeholder' => __('Password'), 'class' => 'form-control login__input']) }}
                            {{ Form::error('password', $errors) }}
                        </div>

                        <div class="form-group d-block w-100 text-right">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link login__lost-password" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="d-block w-100 button button--orange border-0 f-sf-ui-display-bold">
                                {{ __('Login') }}
                            </button>
                        </div>
                        <div class="form-group">
                            <span class="join-text">{{ trans('vcch::login.New to our community?') }}</span> <a class="text-uppercase register-link" href="{{ route('register') }}">{{ trans('vcch::register.Register now') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="{{ Theme::url('images/login-cover.png') }}" alt="Login">
        </div>
    </div>
</div>
@endsection
