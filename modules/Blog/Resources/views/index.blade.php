@extends($themeName."::layouts.master")
@section('content')
    @foreach($posts as $post)
        <div>
            <h1>{{ $post->title }}</h1>
            <div>{!! $post->body !!}</div>
        </div>
    @endforeach
@endsection
