@extends('admin::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ trans('slider::slider.title.Create a slider') }}</div>
            <div class="card-tools">
                <a href="{{ route('admin.slider.index') }}" class="btn">
                    <i class="fa fa-arrow-left"></i>
                    <span>{{ __('Back') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('slider::admin.slider._form')
        </div>
    </div>
@endsection
