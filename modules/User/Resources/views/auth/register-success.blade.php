@extends($themeName.'::layouts.master')

@section('content')
@section('content')
    <div class="container">
        <div class="row justify-content-center mg-t-106 mg-b-200">
            <div class="col-md-6">
                <div class="login">
                    <div class="login__title">
                        <div class="reg-success">
                            <div class="title">{{ trans('vcch::register.Congratulations') }}!</div>
                            <p>
                                {!! trans('vcch::register.reg_success_1') !!}
                            </p>
                            <p>
                                {!! trans('vcch::register.reg_success_2') !!}
                            </p>
                            <a href="/">{!! trans('vcch::register.Back to home') !!} <i class="la la-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="login__body">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ Theme::url('images/login-cover.png') }}" alt="Login">
            </div>
        </div>
    </div>
@endsection
@endsection
