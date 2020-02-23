@extends('admin::layouts.master')
@section('title')
    {{ __('tag::tags.title.Create a tag') }}
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{ __('tag::tags.title.Create a tag') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('admin.tag.index') }}"
                           class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('Back') }}</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @include('tag::admin.tags._form')
        </div>
    </div>
@endsection
