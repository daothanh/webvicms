@extends('admin::layouts.master')
@section('title')
    {{ trans('core::settings.title.General settings') }}
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.settings.company', 'method' => 'post']) }}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ trans('core::core.Multilingual content') }}
            </h3>
            <div class="card-tools">
                <ul class="nav nav-tabs" role="tablist">
                    <?php
                    $locales = locales();
                    $availableLanguages = collect(languages())->pluck('native', 'code')->toArray();
                    $currentLocale = locale();
                    ?>
                    @foreach ($locales as $l )
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link @if($l === $currentLocale) active @endif @if($errors->has($l.'.title') || $errors->has($l.'.slug')) text-danger @endif"
                               data-toggle="tab"
                               href="#{{$l}}" role="tab">{{ $availableLanguages[$l] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                @foreach ($locales as $l)
                    <div class="tab-pane fade show @if($l === $currentLocale) active @endif" id="{{$l}}"
                         role="tabpanel" aria-labelledby="{{$l}}-tab">
                        <div class="form-group @if($errors->has('company.name.'.$l.'')) has-danger @endif">
                            <label>{{ __('core::settings.labels.company.Name') }}</label>
                            {{ Form::text('company[name]['.$l.']', old('company.name.'.$l.'', !empty($company) && !empty($company['name']) && !empty($company['name'][$l]) ? $company['name'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.name.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.name.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.address.'.$l.'')) has-danger @endif">
                            <label>{{ __('core::settings.labels.company.Address') }}</label>
                            {{ Form::text('company[address]['.$l.']', old('company.address.'.$l.'', !empty($company) && !empty($company['address']) && !empty($company['address'][$l]) ? $company['address'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.address.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.address.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.email.'.$l.'')) has-danger @endif">
                            <label>{{ __('core::settings.labels.company.Email') }}</label>
                            {{ Form::text('company[email]['.$l.']', old('company.email.'.$l.'', !empty($company) && !empty($company['email']) && !empty($company['email'][$l]) ? $company['email'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.email.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.email.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.website.'.$l.'')) has-danger @endif">
                            <label>{{ __('core::settings.labels.company.Website') }}</label>
                            {{ Form::text('company[website]['.$l.']', old('company.website.'.$l.'', !empty($company) && !empty($company['website']) && !empty($company['website'][$l]) ? $company['website'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.website.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.website.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.hotline.'.$l.'')) has-danger @endif">
                            <label>{{ __('core::settings.labels.company.Hotline') }}</label>
                            {{ Form::text('company[hotline]['.$l.']', old('company.hotline.'.$l.'', !empty($company) && !empty($company['hotline']) && !empty($company['hotline'][$l]) ? $company['hotline'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.hotline.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.hotline.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.facebook.'.$l.'')) has-danger @endif">
                            <label>{{ __('Facebook') }}</label>
                            {{ Form::text('company[facebook]['.$l.']', old('company.facebook.'.$l.'', !empty($company) && !empty($company['facebook']) && !empty($company['facebook'][$l]) ? $company['facebook'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.facebook.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.facebook.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.instagram.'.$l.'')) has-danger @endif">
                            <label>{{ __('Instagram') }}</label>
                            {{ Form::text('company[instagram]['.$l.']', old('company.instagram.'.$l.'', !empty($company) && !empty($company['instagram']) && !empty($company['instagram'][$l]) ? $company['instagram'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.instagram.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.instagram.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.twitter.'.$l.'')) has-danger @endif">
                            <label>{{ __('Twitter') }}</label>
                            {{ Form::text('company[twitter]['.$l.']', old('company.twitter.'.$l.'', !empty($company) && !empty($company['twitter']) && !empty($company['twitter'][$l]) ? $company['twitter'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.twitter.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.twitter.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.youtube.'.$l.'')) has-danger @endif">
                            <label>{{ __('Youtube') }}</label>
                            {{ Form::text('company[youtube]['.$l.']', old('company.youtube.'.$l.'', !empty($company) && !empty($company['youtube']) && !empty($company['youtube'][$l]) ? $company['youtube'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.youtube.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.youtube.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group @if($errors->has('company.linkedin.'.$l.'')) has-danger @endif">
                            <label>{{ __('Linkedin') }}</label>
                            {{ Form::text('company[linkedin]['.$l.']', old('company.linkedin.'.$l.'', !empty($company) && !empty($company['linkedin']) && !empty($company['linkedin'][$l]) ? $company['linkedin'][$l] : null), ['class' => 'form-control m-input']) }}
                            @if($errors->has('company.linkedin.'.$l.''))
                                <div class="form-control-feedback">
                                    {{ $errors->first('company.linkedin.'.$l.'') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
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
@endpush
