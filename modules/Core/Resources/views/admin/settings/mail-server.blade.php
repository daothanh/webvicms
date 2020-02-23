@extends('admin::layouts.master')
@section('title')
    {{ trans('core::settings.title.Mail Server') }}
@endsection

@section('content')
{{ Form::open(['route' => 'admin.settings.mail-server', 'method' => 'post']) }}
    <div class="card">
        <div class="card-body">
            <div class="form-group @if($errors->has('mailservers.from.name')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.From name') }}</label>
                {{ Form::text('mailservers[mail][from][name]', old('mailservers.mail.from.name', !empty($mailservers['mail']) && !empty($mailservers['mail']['from']) && !empty($mailservers['mail']['from']['name'])? $mailservers['mail']['from']['name'] : null), ['class' => $errors->has('mailservers.mail.from.name') ? 'form-control is-invalid' : 'form-control', 'id' => 's-from-name']) }}
                @if($errors->has('mailservers.mail.from.name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mail.from.name') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has('mailservers.from.email')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.From email') }}</label>
                {{ Form::text('mailservers[mail][from][email]', old('mailservers.mail.from.email', !empty($mailservers['mail']) && !empty($mailservers['mail']['from']) && !empty($mailservers['mail']['from']['email'])? $mailservers['mail']['from']['email'] : null), ['class' => $errors->has('mailservers.mai.from.email') ? 'form-control is-invalid' : 'form-control', 'id' => 's-from-email']) }}
                @if($errors->has('mailservers.mail.from.email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mail.from.email') }}
                    </div>
                @endif
            </div>
            @php $driver = $mailservers['mail']['driver'] ?? 'sendmail'; @endphp
            <div class="form-group">
                <label>{{ __('core::settings.labels.mail.driver') }}</label>
                {{ Form::select('mailservers[mail][driver]', ['sendmail' => 'Sendmail', 'smtp' => 'SMTP', 'mailgun' => 'Mailgun'] ,old('mailservers.mail.driver', !empty($mailservers['mail']) && !empty($mailservers['mail']['driver']) ? $mailservers['mail']['driver'] : 'sendmail'), ['class' => $errors->has('mailservers.mail.driver') ? 'form-control is-invalid' : 'form-control', 'id' => 's-driver']) }}
                @if($errors->has('mailservers.mail.driver'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mail.driver') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'smtp') d-none @endif smtp @if($errors->has('mailservers.smtp.host')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.Host') }}</label>
                {{ Form::text('mailservers[smtp][host]', old('mailservers.smtp.host', !empty($mailservers['smtp']) && !empty($mailservers['smtp']['host']) ? $mailservers['smtp']['host'] : null), ['class' => $errors->has('mailservers.smtp.host') ? 'form-control is-invalid' : 'form-control', 'id' => 's-host']) }}
                @if($errors->has('mailservers.smtp.host'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.smtp.host') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'smtp') d-none @endif smtp @if($errors->has('mailservers.smtp.port')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.Port') }}</label>
                {{ Form::text('mailservers[smtp][port]', old('mailservers.smtp.port', !empty($mailservers['smtp']) && !empty($mailservers['smtp']['port']) ? $mailservers['smtp']['port'] : null), ['class' => $errors->has('mailservers.smtp.port') ? 'form-control is-invalid' : 'form-control', 'id' => 's-port']) }}
                @if($errors->has('mailservers.smtp.port'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.smtp.port') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'smtp') d-none @endif smtp @if($errors->has('mailservers.smtp.encryption')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.Encryption') }}</label>
                {{ Form::text('mailservers[smtp][encryption]', old('mailservers.smtp.encryption', !empty($mailservers['smtp']) && !empty($mailservers['smtp']['encryption']) ? $mailservers['smtp']['encryption'] : null), ['class' => $errors->has('mailservers.smtp.encryption') ? 'form-control is-invalid' : 'form-control', 'id' => 's-encryption']) }}
                @if($errors->has('mailservers.smtp.encryption'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.smtp.encryption') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'smtp') d-none @endif smtp @if($errors->has('mailservers.smtp.username')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.Username') }}</label>
                {{ Form::text('mailservers[smtp][username]', old('mailservers.smtp.username', !empty($mailservers['smtp']) && !empty($mailservers['smtp']['username']) ? $mailservers['smtp']['username'] : null), ['class' => $errors->has('mailservers.smtp.username') ? 'form-control is-invalid' : 'form-control', 'id' => 's-username']) }}
                @if($errors->has('mailservers.smtp.username'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.smtp.username') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'smtp') d-none @endif smtp @if($errors->has('mailservers.smtp.password')) has-danger @endif">
                <label>{{ __('core::settings.labels.mail.Password') }}</label>
                {{ Form::text('mailservers[smtp][password]', old('mailservers.smtp.password', !empty($mailservers['smtp']) && !empty($mailservers['smtp']['password']) ? $mailservers['smtp']['password'] : null), ['class' => $errors->has('mailservers.smtp.password') ? 'form-control is-invalid' : 'form-control', 'id' => 's-password']) }}
                @if($errors->has('mailservers.smtp.password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.smtp.password') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'mailgun') d-none @endif mailgun">
                <label>{{ __('core::settings.labels.mail.mailgun.Domain') }}</label>
                {{ Form::text('mailservers[mailgun][domain]', old('mailservers.mailgun.domain', !empty($mailservers['mailgun']) && !empty($mailservers['mailgun']['domain']) ? $mailservers['mailgun']['domain'] : null), ['class' => $errors->has('mailservers.mailgun.domain') ? 'form-control is-invalid' : 'form-control', 'id' => 's-mailgun-domain']) }}
                @if($errors->has('mailservers.mailgun.domain'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mailgun.domain') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'mailgun') d-none @endif mailgun>
                <label>{{ __('core::settings.labels.mail.mailgun.Secret') }}</label>
                {{ Form::text('mailservers[mailgun][secret]', old('mailservers.mailgun.secret', !empty($mailservers['mailgun']) && !empty($mailservers['mailgun']['secret']) ? $mailservers['mailgun']['secret'] : null), ['class' => $errors->has('mailservers.mailgun.secret') ? 'form-control is-invalid' : 'form-control', 'id' => 's-mailgun-secret']) }}
                @if($errors->has('mailservers.mailgun.secret'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mailgun.secret') }}
                    </div>
                @endif
            </div>
            <div class="form-group sparate @if($driver != 'mailgun') d-none @endif mailgun">
                <label>{{ __('core::settings.labels.mail.mailgun.Endpoint') }}</label>
                {{ Form::text('mailservers[mailgun][endpoint]', old('mailservers.mailgun.endpoint', !empty($mailservers['mailgun']) && !empty($mailservers['mailgun']['endpoint']) ? $mailservers['mailgun']['endpoint'] : null), ['class' => $errors->has('mailservers.mailgun.endpoint') ? 'form-control is-invalid' : 'form-control', 'id' => 's-mailgun-endpoint']) }}
                @if($errors->has('mailservers.mailgun.endpoint'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mailservers.mailgun.endpoint') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="text-center">
                <button class="btn btn-primary m-btn m-btn--icon"><span>
                        <i class="icon ion-md-save"></i>
                        <span>{{ __('Save') }}</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
{{ Form::close() }}
@endsection
@push('js-stack')
    <script>
        $(function () {
            $('#s-driver').change(function () {
                var driver = $(this).val();
                $('.sparate').addClass('d-none');
                $('.'+driver).removeClass('d-none');
            });
        });
    </script>
@endpush
