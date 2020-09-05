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
    <div class="card-header">
        <h3 class="card-title">
            {{ trans('media::media.choose file') }}
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-card-tool jsShowUploadForm">
                <i class="icon ion-md-cloud-upload"></i>
                <span>{{ __('Upload') }}</span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" class="m-dropzone dropzone m-dropzone--primary dropzone my-dropzone mb-3" id="my-dropzone"
              action="{{ route('api.media.store-dropzone') }}" style="display: none">
            {!! Form::token() !!}
            <div class="m-dropzone__msg dz-message needsclick">
                <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
            </div>
        </form>
        <table class="data-table table jsFileList">
            <thead>
            <tr>
                <th width="80">{{ __('File') }}</th>
                <th></th>
                <th>{{ __('Type') }}</th>
                <th width="120">{{ __('Uploaded at') }}</th>
                <th width="200">{{ trans('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script src="{{ Theme::url('js/main.js') }}"></script>
<script>
  var MediaUrls = {
      mediaGridCkEditor: '{{ route('media.grid.ckeditor') }}',
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

<script type="text/javascript">
  $(function () {
    $.ajaxSetup({
      headers: {'Authorization': AuthorizationHeaderValue}
    });
    $('.jsShowUploadForm').on('click', function (event) {
      event.preventDefault();
      $('.dropzone').fadeToggle();
    });

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
      "order": [[3, "desc"]],
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "{{ route('media.grid.ckeditor') }}"
      },
      columnDefs: [
        {
          targets: 0,
          orderable: false,
          data: 'path'
        },
        {
          targets: 1,
          orderable: false,
          data: 'filename'
        },
        {
          targets: 2,
          orderable: false,
          data: 'media_type'
        },
        {
          targets: 3,
          orderable: false,
          data: 'created_at'
        },
        {
          targets: 4,
          orderable: false,
          data: 'created_at'
        }
      ],
      createdRow: function (row, data, dataIndex) {
        if (data.is_image === true) {
          $(row).find('td:eq(0)').html('<img src="' + data.thumbnail + '"/>');
        } else {
          $(row).find('td:eq(0)').html('<i class="la la-file ' + data.fa_icon + '" style="font-size: 20px;"></i>');
        }

        $(row).find('td:eq(1)').html((data.title ? data.title + '<br>' : '') + data.filename);

        let thumbnailMenu = '<div class="btn-group">\n' +
          '                                                                        <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">\n' +
          '                                        Chèn tệp <span class="caret"></span>\n' +
          '                                    </button>\n' + '<div class="dropdown-menu" role="menu">\n';
        if(data.media_type === 'image') {
            $.each(data.thumbnails, function (index, thumbnail) {
                thumbnailMenu += '<a data-file-path="' + thumbnail.path + '" data-id="' + data.id + '" data-media-type="' + data.media_type + '" data-mimetype="' + data.mimetype + '" class="dropdown-item jsInsertImage">\n' +
                    thumbnail.name + ' (' + thumbnail.size + ')' +
                    '  </a>\n';
            });
        }

        thumbnailMenu += '<a class="divider"></a>' +
          '<a data-file-path="' + data.path + '" data-id="' + data.id + '" data-media-type="' + data.media_type + '" data-mimetype="' + data.mimetype + '" class="dropdown-item jsInsertImage">\n' +
          'Original' +
          '  </a>\n' +
          '</div>';
        $(row).find('td:eq(4)').html(thumbnailMenu).find('.jsInsertImage').on('click', insertImageEvent);
      }
    });
  });
</script>

</body>
</html>
