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
<div class="main media-window">
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
<script>
    let selectedItem = null;
    let selectedItems = [];
    const isMultiple = false;
    $.ajaxSetup({
        headers: {Authorization: AuthorizationHeaderValue}
    });
    $('.jsShowUploadForm').on('click', (event) => {
        event.preventDefault();
        $('#my-dropzone').fadeToggle();
    });

    $('body').on('click', '.jsInsertImage', (e) => {
        e.preventDefault();
        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);

            return (match && match.length > 1) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        window.opener.CKEDITOR.tools.callFunction(funcNum, selectedItem.path);
        window.close();
    });
    $('body').on('click', '#thumbnails li', function (e) {
        const item = {
            id: $(this).data('id'),
            path: $(this).data('file-path'),
            type: $(this).data('mediaType'),
            mimetype: $(this).data('mimetype')
        };
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            selectedItem = null;
        } else {
            $('#thumbnails li').removeClass('active');
            $(this).addClass('active');
            selectedItem = item;
        }

        $('#selected-count').html(selectedItem !== null ? 1 : 0);
        if (selectedItem === null) {
            $('.jsInsertImage').attr('disabled', 'disabled');
        } else {
            $('.jsInsertImage').removeAttr('disabled');
        }

    });

    function isSelectedItem(id) {
        let isIn = false;
        if (isMultiple) {
            if (selectedItems.length > 0) {
                $.each(selectedItems, (index, item) => {
                    if (item.id === id) {
                        isIn = true;
                    }
                });
            }
        } else if (selectedItem !== null) {
            isIn = id === selectedItem.id;
        }
        return isIn;
    }

    const cPage = 1;
    let prevPage = 1;
    let nextPage = 1;
    const per_page = 25;

    function loadMedias(page = 1) {
        page = parseInt(page);
        let search = $('#search-file').val()
        axios
            .get(
                `${MediaUrls.mediaGridSelectUrl
                }?page=${
                    page
                }&per_page=${per_page}&search=${
                    search}`
            )
            .then((rs) => {
                const items = rs.data.data;
                if (items !== undefined && items.length > 0) {
                    prevPage = page - 1;

                    if (prevPage < 1) {
                        prevPage = 1;
                    }

                    lastPage = Math.ceil(rs.data.recordsTotal / per_page);
                    nextPage = page + 1;
                    if (nextPage > lastPage) {
                        nextPage = lastPage;
                    }
                    $('#chevron-left').attr('data-page', prevPage);
                    $('#chevron-right').attr('data-page', nextPage);
                    $('#cr-page').html(`${page}/${lastPage}`);
                    $('#thumbnails').html('');
                    $.each(items, (index, item) => {
                        const cls = isSelectedItem(item.id) ? 'active' : '';

                        const ele = `<li title="${item.filename}" class="${
                            cls
                        }" data-id="${
                            item.id
                        }" data-file-path="${
                            item.thumbnail
                        }" data-mimetype="${
                            item.mimetype
                        }" data-media-type="${
                            item.media_type
                        }"><div class="preview">
            <button><span class="fa fa-check"></span></button>
            <div class="thumbnail">
            <div class="centered">${
                            item.media_type === 'image' ? `<img src="${item.thumbnail}"/>` : `<div class="file"><i class="far fa-file"></i></div>`
                        }
          </div>

          </div>
          </div>
          <div class="file-name">${item.filename}</div>
          </li>`;
                        $('#thumbnails').append(ele);
                    });
                } else {
                    $('#thumbnails').html('Không tìm thấy file nào');
                }

            })
            .catch((error) => {
                console.log(error);
            });
    }

    loadMedias();

    $('.load-media').click(function () {
        const page = $(this).attr('data-page');
        loadMedias(page);
    });
    $('#search-file').keyup(function () {
        loadMedias();
    })
</script>
</body>
</html>
