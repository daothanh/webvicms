@extends('simple::layouts.install')
@section('content')
    {{ Form::open(['route' => 'install.app', 'method' => 'post']) }}
    <div class="mt-5 mb-5 card">
        <h1 class="card-header">{{ __('core::core.installation.labels.title') }}</h1>
        <div class="card-body">
            <h3>{{ __('core::core.installation.labels.website') }}</h3>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.app.name') }}</label>
                {{ Form::text('app_name', old('app_name'), ['class' => $errors->has('app_name') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'Google']) }}
                @if($errors->has('app_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('app_name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.app.url') }}</label>
                {{ Form::text('app_url', old('app_url'), ['class' => $errors->has('app_url') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'http://google.com']) }}
                @if($errors->has('app_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('app_url') }}
                    </div>
                @endif
            </div>
            <h3>{{ __('core::core.installation.labels.database_title') }}</h3>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.database.host') }}</label>
                {{ Form::text('db_host', old('db_host'), ['class' => $errors->has('db_host') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'localhost']) }}
                @if($errors->has('db_host'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_host') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.database.port') }}</label>
                {{ Form::text('db_port', old('db_port'), ['class' => $errors->has('db_port') ? 'is-invalid form-control' : 'form-control', 'placeholder' => '3306']) }}
                @if($errors->has('db_port'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_port') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.database.name') }}</label>
                {{ Form::text('db_database', old('db_database'), ['class' => $errors->has('db_database') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'test']) }}
                @if($errors->has('db_database'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_database') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.database.user') }}</label>
                {{ Form::text('db_username', old('db_username'), ['class' => $errors->has('db_username') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'root']) }}
                @if($errors->has('db_username'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_username') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.database.password') }}</label>
                {{ Form::password('db_password', ['class' => $errors->has('db_password') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_password') }}
                    </div>
                @endif
            </div>
            <h3>{{ __('core::core.installation.labels.admin account') }}</h3>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.account.name') }}</label>
                {{ Form::text('user_name', old('user_name'), ['class' => $errors->has('user_name') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'Sundar Pichai']) }}
                @if($errors->has('user_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.account.email') }}</label>
                {{ Form::email('user_email', old('user_email'), ['class' => $errors->has('user_email') ? 'is-invalid form-control' : 'form-control', 'placeholder' => 'sundar@google.com']) }}
                @if($errors->has('user_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_email') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.account.password') }}</label>
                {{ Form::password('user_password', ['class' => $errors->has('user_password') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('user_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_password') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">{{ __('core::core.installation.labels.account.confirm password') }}</label>
                {{ Form::password('user_password_confirmation', ['class' => $errors->has('user_password_confirmation') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('user_password_confirmation'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_password_confirmation') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                {{ Form::submit(__('core::core.installation.labels.install'), ['class' => 'btn btn-primary right']) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection
@push('css-stack')
    <style>
        body {
            background: #eeeef5;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
@endpush
