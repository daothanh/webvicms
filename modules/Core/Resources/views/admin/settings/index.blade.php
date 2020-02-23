@extends('admin::layouts.master')
@section('title')
    {{ trans('core::settings.title.General settings') }}
@endsection

@section('content')
{{ Form::open(['route' => 'admin.settings.store', 'method' => 'post']) }}
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
            @include('core::admin.settings._form', compact('locales'))
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-text">
                {{ trans('core::settings.title.General settings') }}
            </h3>
        </div>
        <div class="card-body">
            <div class="form-group @if($errors->has('s.website.medias_single.logo')) has-danger @endif">
                @mediaSingle('logo', $website ?? null, 'Logo')
                @if($errors->has('medias_single.logo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medias_single.logo') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has('s.website.medias_single.favicon')) has-danger @endif">
                @mediaSingle('favicon', $website ?? null, 'Favicon')
                @if($errors->has('medias_single.favicon'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medias_single.favicon') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has('s.website.script')) has-danger @endif">
                <label>{{ __('core::settings.labels.website.Script') }}</label>
                {{ Form::textarea('s[website][script]', old('s.website.script', !empty($s['website']) && !empty($s['website']['script']) ? $s['website']['script'] : null), ['class' => 'form-control', 'id' => 's-website-script', 'rows' => 10]) }}
                @if($errors->has('s.website.script'))
                    <div class="invalid-feedback">
                        {{ $errors->first('s.website.script') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ __('core::settings.labels.website.Frontend theme') }}</label>
                {{ Form::select('s[website][frontend_theme]', collect(\Theme::themes())->pluck('name', 'code')->toArray() ,old('s.website.frontend_theme', !empty($s['website']) && !empty($s['website']['frontend_theme']) ? $s['website']['frontend_theme'] : 'base'), ['class' => 'form-control', 'id' => 'frontend_theme']) }}
                @if($errors->has('s.website.frontend_theme'))
                    <div class="invalid-feedback">
                        {{ $errors->first('s.website.frontend_theme') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ __('core::settings.labels.website.Admin theme') }}</label>
                {{ Form::select('s[website][admin_theme]', collect(\Theme::themes('backend'))->pluck('name', 'code')->toArray() ,old('s.website.admin_theme', !empty($s['website']) && !empty($s['website']['admin_theme']) ? $s['website']['admin_theme'] : 'admin'), ['class' => 'form-control', 'id' => 'admin_theme']) }}
                @if($errors->has('s.website.admin_theme'))
                    <div class="invalid-feedback">
                        {{ $errors->first('s.website.admin_theme') }}
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
@endpush
