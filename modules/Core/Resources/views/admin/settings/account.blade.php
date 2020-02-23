@extends('admin::layouts.master')

@section('content')
{{ Form::open(['route' => 'admin.settings.account', 'method' => 'post']) }}
    <div class="card">
        <div class="card-body">
            <div class="m-form__group form-group">
                <label for="">{{ trans('core::settings.labels.turn_off_register') }}</label>
                <div class="m-radio-inline">
                    @php $isDisableReg = isset($account['register']) && isset($account['register']['off']) ? $account['register']['off'] : (!config('user.account.register') ? 1 : 0); @endphp
                    <label class="m-radio">
                        <input type="radio" value="1" name="account[register][off]" @if($isDisableReg == 1) checked @endif> {{ __('Yes') }}
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" value="0" name="account[register][off]" @if($isDisableReg == 0) checked @endif> {{ __('No') }}
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="m-form__group form-group">
                <label for="">{{ trans('core::settings.labels.login_by', ['name' => 'Facebook']) }}</label>
                <div class="m-radio-inline">
                    @php $isEnableFacebook = isset($account['facebook']) && isset($account['facebook']['enable']) ? $account['facebook']['enable'] : 0; @endphp
                    <label class="m-radio">
                        <input class="fb" type="radio" value="1" name="account[facebook][enable]" @if($isEnableFacebook == 1) checked @endif> {{ __('Yes') }}
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input class="fb" type="radio" value="0" name="account[facebook][enable]" @if($isEnableFacebook == 0) checked @endif> {{ __('No') }}
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group facebook @if(!$isEnableFacebook) d-none @endif @if($errors->has('account.facebook.client_id')) has-danger @endif">
                <label>{{ __('core::settings.labels.facebook.Client Id') }}</label>
                {{ Form::text('account[facebook][client_id]', old('account.facebook.client_id', isset($account['facebook']) && isset($account['facebook']['client_id']) ? $account['facebook']['client_id'] : null), ['class' => 'form-control m-input', 'id' => 's-from-name']) }}
                @if($errors->has('account.facebook.client_id'))
                    <div class="form-control-feedback">
                        {{ $errors->first('account.facebook.client_id') }}
                    </div>
                @endif
            </div>
            <div class="form-group facebook @if(!$isEnableFacebook) d-none @endif @if($errors->has('account.facebook.client_secret')) has-danger @endif">
                <label>{{ __('core::settings.labels.facebook.Client Secret') }}</label>
                {{ Form::text('account[facebook][client_secret]', old('account.facebook.client_secret', isset($account['facebook']) && isset($account['facebook']['client_secret']) ? $account['facebook']['client_secret'] : null), ['class' => 'form-control m-input', 'id' => 's-from-name']) }}
                @if($errors->has('account.facebook.client_secret'))
                    <div class="form-control-feedback">
                        {{ $errors->first('account.facebook.client_secret') }}
                    </div>
                @endif
            </div>
            <div class="m-form__group form-group">
                <label for="">{{ trans('core::settings.labels.login_by', ['name' => 'Google']) }}</label>
                <div class="m-radio-inline">
                    @php $isEnableGoogle = isset($account['google']) && isset($account['google']['enable']) ? $account['google']['enable'] : 0; @endphp
                    <label class="m-radio">
                        <input type="radio" class="gg" value="1" name="account[google][enable]" @if($isEnableGoogle == 1) checked @endif> {{ __('Yes') }}
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" class="gg" value="0" name="account[google][enable]" @if($isEnableGoogle == 0) checked @endif> {{ __('No') }}
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group google @if(!$isEnableGoogle) d-none @endif @if($errors->has('account.google.client_id')) has-danger @endif">
                <label>{{ __('core::settings.labels.google.Client Id') }}</label>
                {{ Form::text('account[google][client_id]', old('account.google.client_id', isset($account['google']) && isset($account['google']['client_id']) ? $account['google']['client_id'] : null), ['class' => 'form-control m-input', 'id' => 's-from-name']) }}
                @if($errors->has('account.google.client_id'))
                    <div class="form-control-feedback">
                        {{ $errors->first('account.google.client_id') }}
                    </div>
                @endif
            </div>
            <div class="form-group google @if(!$isEnableGoogle) d-none @endif @if($errors->has('account.google.client_secret')) has-danger @endif">
                <label>{{ __('core::settings.labels.google.Client Secret') }}</label>
                {{ Form::text('account[google][client_secret]', old('account.google.client_secret', isset($account['google']) && isset($account['google']['client_secret']) ? $account['google']['client_secret'] : null), ['class' => 'form-control m-input', 'id' => 's-from-name']) }}
                @if($errors->has('account.google.client_secret'))
                    <div class="form-control-feedback">
                        {{ $errors->first('account.google.client_secret') }}
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
            $('input[type=radio].fb').click(function () {
                var onOff = $(this).val();
                console.log(onOff);
                if (onOff === '0') {
                    $('.facebook').addClass('d-none');
                } else {
                    $('.facebook').removeClass('d-none');
                }
            });
            $('input[type=radio].gg').click(function () {
                var onOff = $(this).val();
                console.log(onOff);
                if (onOff === '0') {
                    $('.google').addClass('d-none');
                } else {
                    $('.google').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
