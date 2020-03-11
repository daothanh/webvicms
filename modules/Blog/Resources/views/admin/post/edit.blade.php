@extends('admin::layouts.master')
@section('title')
    {{ __('blog::post.title.Edit a post') }}
@endsection
@section('content')
    @include('blog::admin.post._form')
@endsection
