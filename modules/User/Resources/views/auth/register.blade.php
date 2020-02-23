@extends($themeName.'::layouts.master')

@section('content')
@section('content')
    <div class="container">
        <div class="row justify-content-center mg-t-106 mg-b-200">
            <div class="col-md-6">
                <div class="login">
                    <div class="login__title">
                        @if(\Request::get('success'))
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
                        @else
                            {{ __('Register') }}
                            <p>
                                {{ trans('vcch::register.to join in our community') }}.
                            </p>
                        @endif
                    </div>
                    <div class="login__body">
                        @if(\Request::get('success'))

                        @else
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group @if($errors->has('name')) login__error @endif">
                                            {{ Form::text('name', old('name'), ['placeholder' => __('Your name'), 'class' => 'form-control login__input']) }}
                                            {{ Form::error('name', $errors) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group @if($errors->has('email')) login__error @endif">
                                    {{ Form::text('email', old('email'), ['placeholder' => trans('Email'), 'class' => 'form-control login__input']) }}
                                    {{ Form::error('email', $errors) }}
                                </div>

                                <div
                                    class="form-group d-block w-100 mg-b-60  @if($errors->has('accept')) login__error @endif">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="accept" checked value="1"
                                               class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">
                                            {!! trans('vcch::register.accept_label') !!}
                                        </label>
                                    </div>
                                    {{ Form::error('accept', $errors) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="d-block w-100 button button--orange border-0 f-sf-ui-display-bold">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </form>
                        @endif
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
