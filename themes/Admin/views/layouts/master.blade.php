<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ $currentUser->getFirstToken()->access_token }}">
{!! \SEO::generate() !!}
<!-- Theme style -->
    <link rel="stylesheet" href="{{ Theme::url('css/main.css') }}?version=1">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @stack('css-stack')
    @routes('api.*')
</head>
<body class="hold-transition sidebar-mini @if(\Request::cookie('sidebarcollapse')) sidebar-collapse @endif">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav" id="v-menu">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" id="pushmenu"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" data-widget="control-sidebar" data-slide="true" href="#">
                    <img src="/images/flags/{{ locale() }}.png" class="img-circle" width="20"> <span
                        class="text-uppercase">{{ locale() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    @foreach(languages() as $lang)
                        <a href="{{ route('admin.lang.change', ['locale' => $lang->code]) }}" class="dropdown-item">
                            <img src="/images/flags/{{ $lang->code }}.png" alt="{{ $lang->name }}"
                                 class="img-size-32 mr-3 img-circle">
                            <span class="text-muted">{{ $lang->native }}</span>
                        </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="icon ion-md-home"></i>
                    {{ __('Home') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}">
                    <i class="icon ion-md-log-out"></i>
                    {{ __('Sign Out') }}
                </a>

            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin') }}" class="brand-link">
            <span class="brand-text font-weight-light text-uppercase">{{ site_name() }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ $currentUser->avatar() }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('admin.user.edit', ['id' => $currentUser->id]) }}"
                       class="d-block">{{ $currentUser->name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                {!! $sidebar !!}
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        {!! $breadcrumb !!}
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 pb-5">@yield('content')</div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
{{--<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
    </div>
</aside>--}}
<!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            <a href="https://webvi.vn">WebviCMS</a>, version <?php echo env('APP_VERSION', '1.0') ?>
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2018 - <?php echo date('Y'); ?> <a
                href="{{ env('APP_URL') }}">{{ settings('website.name.'.locale(), env('APP_NAME')) }}</a>.</strong> All
        rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!--begin::Global Theme Bundle -->
<script>
    var currentLocale = "{{ app()->getLocale() }}";
    var AuthorizationHeaderValue = 'Bearer {!! $currentUser->getFirstToken()->access_token !!}',
        MediaUrls = {
            mediaGridCkEditor: '{{ route('media.grid.ckeditor') }}',
            mediaGridSelectUrl: '{{ route('media.grid.select') }}',
            dropzonePostUrl: '{{ route('api.media.store-dropzone') }}',
            mediaSortUrl: '{{ route('api.media.sort') }}',
            mediaLinkUrl: '{{ route('api.media.link') }}',
            mediaUnlinkUrl: '{{ route('api.media.unlink') }}'
        }, maxFilesize = '<?php echo config('media.max-file-size') ?>',
        acceptedFiles = '<?php echo config('media.allowed-types') ?>';
    var languages = {!! json_encode(config("locales")) !!};
</script>
<!-- App functions -->
<script src="{{ Theme::url('js/main.js') }}?version=1"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {'Authorization': 'Bearer {!! \Auth::user()->getFirstToken()->access_token !!}'}
        });
        $('#pushmenu').click(function () {
            if (!$('body').hasClass('sidebar-collapse')) {
                $.ajax({
                    url: '{!! urldecode(route('admin.set.cookie', ['cookie_name' => 'sidebarcollapse', 'cookie_value' => 1])) !!}'
                })
            } else {
                $.ajax({
                    url: '{!! urldecode(route('admin.set.cookie', ['cookie_name' => 'sidebarcollapse', 'cookie_value' => false])) !!}'
                })
            }
        });
    });
</script>
@stack('js-stack')
</body>
</html>
