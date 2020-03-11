@extends('admin::layouts.master')
@section('title')
    {{ __('blog::post.title.Create a post') }}
@endsection
@section('content')
    @include('blog::admin.category._form')
@endsection
