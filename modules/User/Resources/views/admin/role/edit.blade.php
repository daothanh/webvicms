@extends('admin::layouts.master')
@section('title')
    {{ __('Edit a role') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('Edit a role') }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.role.index') }}" class="btn">
                    <i class="icon ion-md-arrow-back"></i>
                    <span>{{ __('Back') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('user::admin.role._form')
        </div>
    </div>
@endsection
