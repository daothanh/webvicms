<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ Auth::user()->getFirstToken()->access_token }}">
    <title>{{ trans('media::media.file picker') }}</title>
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ trans('media::media.choose file') }}
            </h3>
            <div class="card-tools">
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" id="search-file" placeholder="{{ __('Search') }}">
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-info btn-card-tool jsShowUploadForm">
                            <i class="icon ion-md-cloud-upload"></i>
                            <span>{{ __('Upload') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" class="m-dropzone dropzone m-dropzone--primary dropzone my-dropzone mb-3" id="my-dropzone" action="{{ route('api.media.store-dropzone') }}" style="display: none">
                {!! Form::token() !!}
                <div class="m-dropzone__msg dz-message needsclick">
                    <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                </div>
            </form>
            <ul id="thumbnails"></ul>
        </div>
        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="paging m--align-center">
                        <button class="btn btn-info load-media" id="chevron-left" data-page="1"><i class="fa fa-chevron-left"></i></button>
                        <span class="current-page" id="cr-page">1/1</span>
                        <button class="btn btn-info load-media" id="chevron-right" data-page="1"><i class="fa fa-chevron-right"></i></button>
                        <span>Đã chọn <span id="selected-count">0</span> </span>

                    </div>
                <button type="button" disabled="disabled" class="btn btn-outline-info jsInsertImage"><i class="fa fa-check-circle"></i> <span>{{ trans('media::media.insert') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- App functions -->
<script src="{{ Theme::url('js/main.js') }}"></script>
<script>
    var MediaUrls = {
            mediaGridCkEditor : '{{ route('media.grid.ckeditor') }}',
            mediaGridSelectUrl: '{{ route('media.grid.select') }}',
            dropzonePostUrl: '{{ route('api.media.store-dropzone') }}',
            mediaSortUrl: '{{ route('api.media.sort') }}',
            mediaLinkUrl: '{{ route('api.media.link') }}',
            mediaUnlinkUrl: '{{ route('api.media.unlink') }}'
        }, maxFilesize = '<?php echo config('media.max-file-size') ?>',
        acceptedFiles = '<?php echo config('media.allowed-types') ?>',
        AuthorizationHeaderValue = 'Bearer {!! \Auth::user()->getFirstToken()->access_token !!}';
</script>
<script src="{{ Theme::url('js/init-dropzone.js') }}"></script>
<script src="{{ Theme::url('js/general-grid.js') }}"></script>
<script>


</script>
</body>
</html>
