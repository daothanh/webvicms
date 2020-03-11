@extends('admin::layouts.master')
@section('title')
    {{ __('Duplicate') }}
@endsection
@section('content')
    @include('page::admin.post._duplicate_form')
@endsection
