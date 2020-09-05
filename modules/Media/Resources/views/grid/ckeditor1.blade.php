<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta id="token" name="token" content="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ Auth::user()->getFirstToken()->access_token }}">
    <title>{{ trans('media::media.file picker') }}</title>
    <link rel="stylesheet" href="{{ Theme::url('css/main.css') }}">
</head>
<body>
<div class="card">
    <div class="card-body">
        <h3 class="card-title">
            {{ trans('media::media.choose file') }}
        </h3>
        <a href="#"
           class="btn btn-primary btn-card-tool jsShowUploadForm btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
												<span>
													<i class="la la-cloud-upload"></i>
													<span>{{ __('Upload') }}</span>
												</span>
        </a>
        <form method="POST" class="m-dropzone dropzone m-dropzone--primary dropzone my-dropzone mb-3" id="my-dropzone"
              action="{{ route('api.media.store-dropzone') }}" style="display: none">
            {!! Form::token() !!}
            <div class="m-dropzone__msg dz-message needsclick">
                <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
            </div>
        </form>
        <table class="data-table table table-bordered table-hover jsFileList data-table">
            <thead>
            <tr>
                <th>{{ trans('Thumbnail') }}</th>
                <th>{{ trans('File name') }}</th>
                <th data-sortable="false">{{ trans('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    var MediaUrls = {
            mediaGridCkEditor: '{{ route('media.public.grid.ckeditor') }}',
            mediaGridSelectUrl: '{{ route('media.public.grid.select') }}',
            dropzonePostUrl: '{{ route('media.public.store-dropzone') }}'
        }, maxFilesize = '<?php echo config('media.max-file-size') ?>',
        acceptedFiles = '<?php echo config('media.allowed-types') ?>',
        AuthorizationHeaderValue = 'Bearer {!! \Auth::user()->getFirstToken()->access_token !!}';
</script>
<script src="{{ Theme::url('js/main.js') }}"></script>

<script type="text/javascript">
    $(function () {
        function insertImageEvent(e) {
            e.preventDefault();

            function getUrlParam(paramName) {
                var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                var match = window.location.search.match(reParam);

                return (match && match.length > 1) ? match[1] : null;
            }

            var funcNum = getUrlParam('CKEditorFuncNum');

            window.opener.CKEDITOR.tools.callFunction(funcNum, $(this).data('file-path'));
            window.close();
        }

        $('.data-table').dataTable({
            "paginate": true,
            "lengthChange": true,
            "lengthMenu": [25, 50, 100],
            "pageLength": 25,
            "filter": true,
            "sort": true,
            "info": true,
            "autoWidth": true,
            "order": [[1, "desc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('media.public.grid.ckeditor') }}"
            },
            "columns": [
                // {"data": "id", 'searchable': true, "orderable": true},
                {"data": "thumbnail", 'searchable': false, "orderable": false},
                {"data": "filename", 'searchable': true, "orderable": false},
                {"data": "path", 'searchable': false, "orderable": false},
            ],
            createdRow: function (row, data, dataIndex) {
                // $(row).find('td:eq(0)').html(data.id);
                if (data.is_image === true) {
                    $(row).find('td:eq(0)').html('<img src="' + data.thumbnail + '"/>');
                } else {
                    $(row).find('td:eq(0)').html('<i class="la la-file ' + data.fa_icon + '" style="font-size: 20px;"></i>');
                }

                $(row).find('td:eq(1)').html(data.filename);
                let thumbnailMenu = '<div class="btn-group">\n' +
                    '                                                                        <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">\n' +
                    '                                        Chèn tệp <span class="caret"></span>\n' +
                    '                                    </button>\n' + '<div class="dropdown-menu" role="menu">\n';
                $.each(data.thumbnails, function (index, thumbnail) {
                    thumbnailMenu += '<a data-file-path="' + thumbnail.path + '" data-id="' + data.id + '" data-media-type="' + data.media_type + '" data-mimetype="' + data.mimetype + '" class="dropdown-item jsInsertImage">\n' +
                        thumbnail.name + ' (' + thumbnail.size + ')' +
                        '  </a>\n';
                });
                thumbnailMenu += '<a class="divider"></a>' +
                    '<a data-file-path="' + data.path + '" data-id="' + data.id + '" data-media-type="' + data.media_type + '" data-mimetype="' + data.mimetype + '" class="dropdown-item jsInsertImage">\n' +
                    'Original' +
                    '  </a>\n' +
                    '</div>';
                $(row).find('td:eq(2)').html(thumbnailMenu).find('.jsInsertImage').on('click', insertImageEvent);
            }
        });
    });
</script>

</body>
</html>
