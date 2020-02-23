@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            @include('media::admin.media._form')
        </div>
    </div>
@endsection
