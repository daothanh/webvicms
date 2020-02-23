@extends('admin::layouts.master')
@section('title')
    {{ __('Create a user') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('Create a user') }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.index') }}" class="btn">
                    <i class="fa fa-arrow-left"></i>
                    <span>{{ __('Back') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link active @if($errors->has('user.name') || $errors->has('user.email')) text-danger @endif"
                       data-toggle="tab" href="#user" role="tab"
                       aria-controls="pills-home" aria-selected="true">{{ __('User information') }}</a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#role" role="tab"
                       aria-controls="role-tab">{{ __('Role') }}</a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link @if($errors->has('password'))is-invalid @endif" data-toggle="tab" href="#new-password"
                       role="tab"
                       aria-controls="role-tab">{{ __('New password') }}</a>
                </li>
            </ul>
            @include('user::admin.user._form')
        </div>
    </div>
@endsection
