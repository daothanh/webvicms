@extends('admin::layouts.master')
@section('title')
    {{ __('page::page.title.Create a page') }}
@endsection
@section('content')
    @include('page::admin.page._form')
@endsection
