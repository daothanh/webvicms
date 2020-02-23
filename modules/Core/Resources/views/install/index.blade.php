@extends('simple::layouts.install')
@section('content')
    {{ Form::open(['route' => 'install.app', 'method' => 'post']) }}
    <div class="mt-5 mb-5 card">
        <h1 class="card-header">Cài đặt</h1>
        <div class="card-body">
            <h3>Website</h3>
            <div class="form-group">
                <label for="">Tên</label>
                {{ Form::text('app_name', old('app_name'), ['class' => $errors->has('app_name') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('app_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('app_name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Url</label>
                {{ Form::text('app_url', old('app_url'), ['class' => $errors->has('app_url') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('app_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('app_url') }}
                    </div>
                @endif
            </div>
            <h3>Cơ sở dữ liệu</h3>
            <div class="form-group">
                <label for="">Host</label>
                {{ Form::text('db_host', old('db_host'), ['class' => $errors->has('db_host') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_host'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_host') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Port</label>
                {{ Form::text('db_port', old('db_port'), ['class' => $errors->has('db_port') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_port'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_port') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Database</label>
                {{ Form::text('db_database', old('db_database'), ['class' => $errors->has('db_database') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_database'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_database') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">User</label>
                {{ Form::text('db_username', old('db_username'), ['class' => $errors->has('db_user') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_username'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_username') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Password</label>
                {{ Form::password('db_password', ['class' => $errors->has('db_password') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('db_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('db_password') }}
                    </div>
                @endif
            </div>
            <h3>Tài khoản quản trị</h3>
            <div class="form-group">
                <label for="">Họ và tên</label>
                {{ Form::text('user_name', old('user_name'), ['class' => $errors->has('user_name') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('user_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_name') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Email</label>
                {{ Form::email('user_email', old('user_email'), ['class' => $errors->has('user_email') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('user_email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_email') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Password</label>
                {{ Form::password('user_password', ['class' => $errors->has('user_password') ? 'is-invalid form-control' : 'form-control']) }}
                @if($errors->has('user_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_password') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                {{ Form::submit('Cài đặt', ['class' => 'btn btn-primary right']) }}
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