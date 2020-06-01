@extends('admin::layouts.master')
@section('title')
    {{ __('slider::slider.title.Edit a slider') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ $slider->title }}</div>
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
