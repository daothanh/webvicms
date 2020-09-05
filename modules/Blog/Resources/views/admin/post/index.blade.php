@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ \SEO::getTitle() }}</div>
            <div class="card-tools">
                <a href="{{ route('admin.blog.post.create') }}" class="btn"><i
                            class="icon ion-md-add"></i> {{ __('blog::post.title.Create a post') }}</a>
            </div>
        </div>
        <div class="card-body">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3" id="filters">
                <a class="navbar-brand" href="#"><i class="fa fa-filter"></i> {{ __('Filters') }}</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown filter-status">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Status') }} <span id="selected-status"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item filter" data-field="status" data-value="1"
                                   href="#">{{ __('Show') }}</a>
                                <a class="dropdown-item filter" data-field="status" data-value="0"
                                   href="#">{{ __('Hidden') }}</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link trash filter" data-field="is_trashed" data-value="1" href="#"><i
                                        class="icon ion-md-trash"></i> {{ __('Trash') }}</a>
                        </li>
                    </ul>
                    <div class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2 search" type="text" placeholder="Search" aria-label="Search">
                        <button class="btn btn-default my-2 my-sm-0" type="button" id="clear-filters"><i
                                    class="fa fa-eraser"></i> {{ __("Clear filters") }}</button>
                    </div>
                </div>
            </nav>
            <button class="btn btn-danger" id="delete-all" disabled><i class="icon ion-md-close"></i> {{ __('Delete') }}
            </button>
            <button class="btn btn-info" id="restore-all" style="display: none" disabled><i
                    class="icon ion-md-refresh"></i> {{ __('Restore') }}
            </button>
            <table id="data-table" class="table table-striped">
                <thead class="thead-default">
                <tr>
                    <th colspan="1" width="28" data-col="0">
                        <input type="checkbox" id="mass-select-all" name="mass-select-all">
                    </th>
                    <th width="60"> {{ __('Image') }}</th>
                    <th>{{ __('blog::post.labels.Title') }}</th>
                    <th width="100">{{ __('Date') }}</th>
                    <th>{{ __('Seo title') }}</th>
                    <th>{{ __('Seo description') }}</th>
                    <th>{{ __('Seo keywords') }}</th>
                    <th width="50">{{ __('Status') }}</th>
                    <th width="100">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js-stack')
    <script>
      $(function () {
        var postTable = $('#data-table').DataTable({
          autoWidth: false,
          responsive: true,
          bFilter: false,
          lengthChange: false,
          postLength: 25,
          serverSide: true,
          ajax: {
            url: "{{ route('api.blog.post.index') }}",
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
          processing: true,
          columnDefs: [
            {
              'targets': 0,
              'orderable': false,
              'data': function (row, type, val, meta) {
                return '<input name="entryId[]" type="checkbox" data-deleted="' + (row.deleted_at !== null ? '1' : '') + '" value="' + row.id + '" class="del-checkbox">';
              }
            },
            {
              targets: 1,
              orderable: false,
              data: function (row, type, val, meta) {
                var imgPath = (row.image !== null && row.image !== '') ? row.image.thumbnail : '{{ asset('storage/media/no-image.jpg') }}'
                return '<img width="50" src="' + imgPath + '"/>';
              }
            },
            {
              targets: 2,
              orderable: false,
              data: function (row, type, val, meta) {
                return '<a target="_blank" href="' + row.urls.public + '">' + row[currentLocale].title + '</a>'
              }
            },

            {
              targets: 3,
              orderable: true,
              data: "created_at"
            },
            {
              targets: 4,
              orderable: false,
              data: function (row, type, val, meta) {
                return row.seo !== null ? row.seo.title : '';
              }
            },
            {
              targets: 5,
              orderable: false,
              data: function (row, type, val, meta) {
                return row.seo !== null ? row.seo.description : '';
              }
            },
            {
              targets: 6,
              orderable: false,
              data: function (row, type, val, meta) {
                return row.seo !== null ? row.seo.keywords : '';
              }
            },
            {
              targets: 7,
              orderData: [7],
              name: 'status',
              orderable: true,
              data: 'status'
            },
            {
              targets: 8,
              orderable: false,
              data: function (row, type, val, meta) {
                var buttons = '';
                if (row.deleted_at !== null) {
                  buttons += '<a href="' + row.urls.restore + '" class="btn btn-danger btn-sm restore" data-toggle="tooltip" data-title="{{ __("Restore") }}"><i class="icon ion-md-refresh"></i> {{ __("Restore") }}</a>\n';
                } else {
                  buttons += '<a href="' + row.urls.edit + '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n';
                  buttons += '<a href="' + row.urls.duplicate + '" class="btn btn-info btn-sm" data-toggle="tooltip" data-title="{{ __("Duplicate") }}"><i class="icon ion-md-copy"></i></a>\n';
                  buttons += '<a href="' + row.urls.delete + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>\n';
                }
                return buttons;
              }
            }
          ],
          createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(7)').html('<div class="custom-control custom-switch">\n' +
              '                      <input type="checkbox" class="custom-control-input toggle-status" data-id="' + data.id + '" ' + (data.status == 1 ? 'checked="checked"' : '') + ' id="customSwitch-' + data.id + '">\n' +
              '                      <label class="custom-control-label" for="customSwitch-' + data.id + '"></label>\n' +
              '                    </div>');

          },
          order: [[3, "desc"], [7, "desc"]],
        });
        $('.search').keyup(function (e) {
          e.preventDefault();
          var val = $(this).val();
          if (val.length >= 3 || val === '') {
            postTable.draw();
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
          postTable.draw();
        });
        $('#clear-filters').click(function () {
          $('.filter').each(function () {
            var field = $(this).data('field');
            $(this).removeClass('active');
            $('#selected-' + field).html('');
            $('.filter-' + field).removeClass('active');

            $('.search').val('');
            postTable.draw();
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
            $('#restore-all').removeAttr('disabled');
          } else {
            $('#delete-all').attr('disabled', 'disabled');
            $('#restore-all').attr('disabled', 'disabled');
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
            $('#restore-all').removeAttr('disabled');
          } else {
            $('#delete-all').attr('disabled', 'disabled');
            $('#restore-all').attr('disabled', 'disabled');
          }
        }).on('click', '.delete', function (e) {
          e.preventDefault();
          let url = $(this).attr('href');
          swal({
            title: '{{ __("Are you sure?") }}',
            text: "{{ __("It will be moved to the trash!") }}",
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
                  if (rs.data.error === false) {
                    postTable.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("It was moved to the trash!") }}', 'success');
                  } else {
                    notify('{{ __("Alert") }}', rs.data.error, 'error');
                  }
                })
                .catch(function (error) {
                  notify('{{ __("Alert") }}', error.response.data.message, 'error');
                });
            }
          });
        }).on('click', '.restore', function (e) {
          e.preventDefault();
          let url = $(this).attr('href');
          swal({
            title: '{{ __("Are you sure?") }}',
            text: "{{ __("It will be restored!") }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '{{ __("Cancel") }}',
            confirmButtonText: '{{ __("Yes, restore it!") }}'
          }).then((result) => {
            if (result.value) {
              axios.post(url)
                .then(rs => {
                  if (rs.data.error === false) {
                    postTable.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("It was restored!") }}', 'success');
                  } else {
                    notify('{{ __("Alert") }}', rs.data.error, 'error');
                  }
                })
                .catch(function (error) {
                  notify('{{ __("Alert") }}', error.response.data.message, 'error');
                });
            }
          });
        }).on('click', '.toggle-status', function () {
          var checked = $(this).prop('checked');
          axios.post('{{ route("api.blog.post.toggle_status") }}', {id: $(this).data('id')});
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
              text: "{{ __("All selected posts will be moved to the trash!") }}",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: '{{ __("Cancel") }}',
              confirmButtonText: '{{ __("Yes, delete it!") }}'
            }).then((result) => {
              if (result.value) {
                axios.post("{{ route('api.blog.post.delete-multiple') }}", {ids: ids})
                  .then(function (rs) {
                    postTable.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("All selected posts were moved to the trash!") }}', 'success');
                  })
                  .catch(function (error) {
                    notify('{{ __("Alert") }}', error.response.data.message, 'error');
                  });
              }
            });
          }
        });
        $('#restore-all').click(function () {
          var ids = [];
          $('input[name="entryId[]"]').each(function () {
            if ($(this).prop("checked")) {
              ids.push($(this).val());
            }
          });
          if (ids.length) {
            swal({
              title: '{{ __("Are you sure?") }}',
              text: "{{ __("All selected posts will be restored!") }}",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: '{{ __("Cancel") }}',
              confirmButtonText: '{{ __("Yes") }}'
            }).then((result) => {
              if (result.value) {
                axios.post("{{ route('api.blog.post.restore-multiple') }}", {ids: ids})
                  .then(function (rs) {
                    postTable.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("All selected posts were restored!") }}', 'success');
                  })
                  .catch(function (error) {
                    notify('{{ __("Alert") }}', error.response.data.message, 'error');
                  });
              }
            });
          }
        });
        $('.trash').click(function () {
          $('#mass-select-all').removeAttr('checked');
          if ($(this).hasClass('active')) {
            $('#restore-all').attr('disabled', 'disabled').show();
            $('#delete-all').hide();
          } else {
            $('#restore-all').hide();
            $('#delete-all').attr('disabled', 'disabled').show();
          }
        });
      });
    </script>

@endpush
