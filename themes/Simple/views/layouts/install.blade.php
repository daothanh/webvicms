<!DOCTYPE html>
<html lang="{{ locale() }}">
<head>
    <meta charset="utf-8">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ Auth::check() ? Auth::user()->getFirstToken()->access_token : '' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {!! SEO::generate() !!}
    <link rel="preload" href="{{ Theme::url('css/main.css') }}" as="style">
    <link rel="preload" href="{{ Theme::url('js/main.js') }}" as="script">

    <link rel="stylesheet" href="{{ Theme::url('css/main.css') }}">
    @stack('css-stack')
</head>
<body>
<div class="container">
    @yield('content')
</div>
<script>
    window.Webvi = {
        locale: "{{ locale() }}",
        locale_prefix: '{{ locale_prefix() }}',
        assets: {
            themeUrl: '{{ Theme::url('') }}',
        }
    };
</script>
<!-- App functions -->
<script src="{{ Theme::url('js/main.js') }}"></script>
{!! \Settings::get('website', 'script') !!}
@stack('js-stack')
</body>
</html>
