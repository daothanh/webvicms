@extends($themeName."::layouts.email")
@section('content')
    <h1>Contact Information</h1>
    <p>Name: {{ $data['name'] }}</p>
    <p>Email: {{ $data['email'] }}</p>
    <p>Subject: {{ $data['subject'] }}</p>
    <p>Content: {{ $data['content'] }}</p>
@endsection
