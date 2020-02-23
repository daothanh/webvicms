<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ Auth::user()->getFirstToken()->access_token }}">
    <title>{{ trans('media::media.file picker') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="{{ Theme::url('css/main.css') }}">
    <style>
        .jsInsertImage {
            position: absolute;
            top: 0;
            right: 2.2rem;
        }
        .m-portlet__footer {
            position: fixed;
            bottom: 0;
            left:0;
            width: 100%;
            height: 50px;
            padding: 5px;
            background: #fff;
        }
        .paging .current-page {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h1 class="m-portlet__head-text">
                        {{ trans('media::media.choose file') }}
                    </h1>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="#"
                           class="btn btn-info btn-card-tool jsShowUploadForm btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-cloud-upload"></i>
                                <span>{{ __('Upload') }}</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="POST" class="m-dropzone dropzone m-dropzone--primary dropzone my-dropzone mb-3" id="my-dropzone" action="{{ route('api.media.store-dropzone') }}" style="display: none">
                {!! Form::token() !!}
                <div class="m-dropzone__msg dz-message needsclick">
                    <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                </div>
            </form>
            <ul id="thumbnails"></ul>
        </div>
        <div class="m-portlet__foot">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="paging m--align-center">
                        <button class="btn btn-info load-media" id="chevron-left" data-page="1"><i class="la la-chevron-left"></i></button>
                        <span class="current-page" id="cr-page">1/1</span>
                        <button class="btn btn-info load-media" id="chevron-right" data-page="1"><i class="la la-chevron-right"></i></button>
                        <span>Đã chọn <span id="selected-count">0</span> </span>

                    </div>
                <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--icon m-btn--outline-2x m-btn--pill m-btn--air jsInsertImage"><span>{{ trans('media::media.insert') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- App functions -->
<script>
    var MediaUrls = {
            mediaGridCkEditor : '{{ route('media.grid.ckeditor') }}',
            mediaGridSelectUrl: '{{ route('media.public.grid.select') }}',
            dropzonePostUrl: '{{ route('media.public.store-dropzone') }}',
        }, maxFilesize = '<?php echo config('media.max-file-size') ?>',
        acceptedFiles = '<?php echo config('media.allowed-types') ?>',
        AuthorizationHeaderValue = 'Bearer {!! \Auth::user()->getFirstToken()->access_token !!}';
</script>
<script src="{{ Theme::url('js/main.js') }}"></script>
<script>
    $(function () {
      loadMedias();
      $('.load-media').click(function () {
        const page = $(this).attr('data-page');
        loadMedias(page);
      });
    })
</script>
</body>
</html>
