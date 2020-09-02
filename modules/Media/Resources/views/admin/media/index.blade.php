@extends('admin::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-danger" id="delete-all" disabled><i class="icon ion-md-close"></i> {{ __('Delete') }}
            </button>
            <div class="card-tools">
                <button type="button" id="btn-upload-toggle" class="btn btn-primary">
                    <i class="icon ion-md-cloud-upload"></i> {{ __('Upload') }}
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="upload-box" class="d-none mt-3">
                <form class="m-dropzone dropzone m-dropzone--primary dropzone my-dropzone mb-3" id="my-dropzone"
                      action="{{ route('api.media.store-dropzone') }}">
                    <div class="m-dropzone__msg dz-message needsclick">
                        <h3 class="m-dropzone__msg-title">Drop files here or click to upload.</h3>
                    </div>
                </form>
                {{ __('Max upload file size') }}: {{ round(file_upload_max_size()/pow(1024, 2)) }} MB
            </div>
            <div class="table-responsive">
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 mt-3" id="filters">
                    <a class="navbar-brand" href="#"><i class="fa fa-filter"></i> {{ __('Filters') }}</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item dropdown filter-type">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Type') }} <span id="selected-type"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item filter" data-field="type" data-value=""
                                       href="#">{{ __('All') }}</a>
                                    <a class="dropdown-item filter" data-field="type" data-value="image"
                                       href="#">{{ __('Image') }}</a>
                                    <a class="dropdown-item filter" data-field="type" data-value="video"
                                       href="#">{{ __('Video') }}</a>
                                    <a class="dropdown-item filter" data-field="type" data-value="audio"
                                       href="#">{{ __('Audio') }}</a>
                                    <a class="dropdown-item filter" data-field="type" data-value="application"
                                       href="#">{{ __('Other') }}</a>
                                </div>
                            </li>
                        </ul>
                        <div class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2 search" type="text" placeholder="Search"
                                   aria-label="Search">
                            <button class="btn btn-default my-2 my-sm-0" type="button" id="clear-filters"><i
                                        class="fa fa-eraser"></i> {{ __("Clear filters") }}</button>
                        </div>
                    </div>
                </nav>
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th colspan="1" width="28" data-col="0">
                            <input type="checkbox" id="mass-select-all" name="mass-select-all">
                        </th>
                        <th width="50">{{ __('File') }}</th>
                        <th></th>
                        <th>{{ __('Type') }}</th>
                        <th width="120">{{ __('Uploaded at') }}</th>
                        <th width="120">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js-stack')
    <script>
      $(function () {
        var dt;
        dt = $('#data-table').DataTable({
          autoWidth: false,
          responsive: true,
          bFilter: false,
          lengthChange: false,
          pageLength: 25,
          serverSide: true,
          processing: true,
          ajax: {
            url: "{{ route('api.media.index') }}",
            data: function (data) {
              $('.filter').each(function () {
                var isActived = $(this).hasClass('active');
                if (isActived) {
                  data[$(this).data('field')] = $(this).data('value');
                }
              });
              data.search = $('.search').val();
            }
          },
          columnDefs: [
            {
              targets: 0,
              orderable: false,
              data: function (row, type, val, meta) {
                return '<input name="entryId[]" type="checkbox" data-deleted="' + (row.deleted_at !== null ? '1' : '') + '" value="' + row.id + '" class="del-checkbox">';
              }
            },
            {
              targets: 1,
              orderable: false,
              data: 'path'
            },
            {
              targets: 2,
              orderable: false,
              data: 'filename'
            },
            {
              targets: 3,
              orderable: false,
              data: 'media_type'
            },
            {
              targets: 4,
              orderable: false,
              data: 'created_at'
            },
            {
              targets: 5,
              orderable: false,
              data: 'path'
            }
          ],
          order: [[4, "desc"]],
          createdRow: function (row, data, dataIndex) {
            if (data.is_image) {
              $(row).find('td:eq(1)').html('<img width="80" src="' + data.thumbnail + '">');
            } else {
              $(row).find('td:eq(1)').html('<i style="font-size: 24px;" class="' + data.fa_icon + '"></i>');
            }
            $(row).find('td:eq(2)').html((data.title ? data.title + '<br>' : '') + data.filename);
            $(row).find('td:eq(3)').html('<span class="text-capitalize">' + data.media_type + '</span>');
            $(row).find('td:eq(5)').html('<a href="' + data.urls.edit_url + '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n' +
              '                                <a href="' + data.urls.delete_url + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>');
          }
        });

        $('.search').keyup(function (e) {
          e.preventDefault();
          var val = $(this).val();
          if (val.length >= 3 || val === '') {
            dt.draw();
          }
          return false;
        });
        $('.filter').click(function (e) {
          e.preventDefault();
          var field = $(this).data('field'), $this = $(this);

          $('.filter').each(function () {
            if ($(this).data('field') === field && $this[0] !== $(this)[0]) {
              $(this).removeClass('active');
            }
          });
          $this.toggleClass('active');

          var selectedEle = $('#selected-' + field);
          if ($this.hasClass('active')) {
            if (selectedEle.length) {
              selectedEle.html(' - ' + $this.text());
            }
            $('.filter-' + field).addClass('active');
          } else {
            selectedEle.html('');
            $('.filter-' + field).removeClass('active');
          }
          dt.draw();
        });
        $('#clear-filters').click(function () {
          $('.filter').each(function () {
            var field = $(this).data('field');
            $(this).removeClass('active');
            $('#selected-' + field).html('');
            $('.filter-' + field).removeClass('active');
            $('.search').val('');
            dt.draw();
          });
        });

        $('body').on('click', '.del-checkbox', function () {
          var checked = $(this).prop('checked'), rows = $('#data-table').find('tbody tr'), colIndex = 0;
          $.each(rows, function () {
            if (!checked) {
              checked = $($(this).find('td').eq(colIndex)).find('input').prop('checked');
            }
          });
          if (checked) {
            $('#delete-all').removeAttr('disabled');
          } else {
            $('#delete-all').attr('disabled', 'disabled');
          }
        }).on('click', '#mass-select-all', function () {
          var rows, checked, colIndex;
          rows = $('#data-table').find('tbody tr');
          checked = $(this).prop('checked');
          colIndex = 0;
          $.each(rows, function () {
            $($(this).find('td').eq(colIndex)).find('input').prop('checked', checked);
          });
          if (checked) {
            $('#delete-all').removeAttr('disabled');
          } else {
            $('#delete-all').attr('disabled', 'disabled');
          }
        }).on('click', '.delete', function (e) {
          e.preventDefault();
          let url = $(this).attr('href');
          swal({
            title: '{{ __("Are you sure?") }}',
            text: "{{ __("You won't be able to revert this!") }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonText: '{{ __("Yes, delete it!") }}'
          }).then((result) => {
            if (result.value) {
              axios.delete(url)
                .then(rs => {
                  if (rs.data.errors === false) {
                    notify('Thông báo', rs.data.message, 'success');
                    dt.ajax.reload();
                  } else {
                    notify('Thông báo lỗi', rs.data.error, 'danger');
                  }
                });
            }
          });
        });

        $('#delete-all').click(function () {
          var ids = [];
          $('input[name="entryId[]"]').each(function () {
            if ($(this).prop("checked")) {
              ids.push($(this).val());
            }
          });
          if (ids.length) {
            swal({
              title: '{{ __("Are you sure?") }}',
              text: "{{ __("All selected files will be deleted!") }}",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: '{{ __("Cancel") }}',
              confirmButtonText: '{{ __("Yes, delete it!") }}'
            }).then((result) => {
              if (result.value) {
                axios.post("{{ route('api.media.delete-multiple') }}", {ids: ids})
                  .then(function (rs) {
                    dt.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("All selected files were deleted!") }}', 'success');
                  })
                  .catch(function (error) {
                    notify('{{ __("Alert") }}', error.response.data.message, 'error');
                  });
              }
            });
          }
        });

        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#my-dropzone", {
          url: MediaUrls.dropzonePostUrl,
          autoProcessQueue: true,
          maxFilesize: maxFilesize,
          acceptedFiles: acceptedFiles
        });
        myDropzone.on("queuecomplete", function (file) {
          console.log(file);
          notify('Thông báo', 'Các file đã được tải lên!', 'success');
          dt.ajax.reload();
          $.each(myDropzone.files, function (index, file) {
            if (file.status === Dropzone.SUCCESS) {
              myDropzone.removeFile(file);
            }
          });

        });
        myDropzone.on("sending", function (file, xhr, fromData) {
          xhr.setRequestHeader("Authorization", AuthorizationHeaderValue);
          if ($('.alert-danger').length > 0) {
            $('.alert-danger').remove();
          }
        });
        myDropzone.on("error", function (file, errorMessage) {
          let html;
          if (typeof (errorMessage) === 'object') {
            html = '<div class="alert alert-danger" role="alert">' + errorMessage.errors.file.join(', ') + '</div>';
          } else {
            html = '<div class="alert alert-danger" role="alert">' + errorMessage + '</div>';
          }
          $('.dropzone').first().parent().prepend(html);
          setTimeout(function () {
            myDropzone.removeFile(file);
          }, 2000);
        });

        $('#btn-upload-toggle').click(function (e) {
          e.preventDefault();
          if ($('#upload-box').hasClass('d-none')) {
            $('#upload-box').removeClass('d-none');
          } else {
            $('#upload-box').addClass('d-none');
          }
        })
      });
    </script>
@endpush
