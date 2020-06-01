@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                {{ $slider->title }}
            </div>
            <div class="card-tools">
                <a href="{{ route('admin.slider.item.index', ['slider' => $slider->id]) }}" class="btn"><i
                        class="icon ion-md-arrow-back"></i> {{ __('Back') }}</a>
            </div>
        </div>
        <div class="card-body">
            @include('slider::admin.slider.slide._form')
        </div>
    </div>
@endsection
