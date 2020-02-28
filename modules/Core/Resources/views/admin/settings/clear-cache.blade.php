@extends('admin::layouts.master')
@section('title')
    {{ trans('core::settings.title.Clear cache') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ trans('core::core.Clear cache') }}
            </h3>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
                {{ __('core::core.All cache were cleared') }}
            </div>
        </div>
    </div>
@endsection
