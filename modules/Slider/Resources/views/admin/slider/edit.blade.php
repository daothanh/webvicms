@extends('admin::layouts.master')
@section('title')
    {{ __('slider::slider.title.Edit a slider') }}
@endsection
@section('content')
    @include('slider::admin.slider._form')
@endsection
