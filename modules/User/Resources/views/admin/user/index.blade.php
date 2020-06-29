@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-danger" id="delete-all" disabled><i class="icon ion-md-close"></i> {{ __('Delete') }}
            </button>
            <div class="card-tools">
                <a href="{{ route('admin.user.create') }}" class="btn">
                    <i class="fa fa-plus"></i>
                    <span>{{ __('user::user.title.Create a user') }}</span>
                </a>
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
            <div class="table-responsive">
                <table id="data-table" class="table">
                    <thead class="thead-default">
                    <tr>
                        <th colspan="1" width="28" data-col="0">
                            <input type="checkbox" id="mass-select-all" name="mass-select-all">
                        </th>
                        <th>{{ __('user::user.labels.Picture') }}</th>
                        <th>{{ __('user::user.labels.Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('user::user.labels.Verified Email') }}</th>
                        <th>{{ __('user::user.Roles') }}</th>
                        <th>{{ __('user::user.labels.Registered at') }}</th>
                        <th width="100">{{ __('Actions') }}</th>
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
        // Initiate data-table
        var table1 = $('#data-table').DataTable({
          autoWidth: false,
          responsive: true,
          bFilter: false,
          lengthChange: false,
          pageLength: 25,
          serverSide: true,
          processing: true,
          ajax: {
            url: "{{ route('api.user.index') }}",
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
                var imgPath = (row.avatar !== null && row.avatar !== '') ? row.avatar : '{{ asset('storage/media/no-image.jpg') }}'
                return '<img width="50" src="' + imgPath + '"/>';
              }
            },
            {
              targets: 2,
              orderable: false,
              data: 'name'
            },
            {
              targets: 3,
              orderable: true,
              data: "email"
            },
            {
              targets: 4,
              orderable: true,
              data: "email_verified_at"
            },
            {
              targets: 5,
              orderable: false,
              data: "roles"
            },
            {
              targets: 6,
              orderable: true,
              data: "created_at"
            },
            {
              targets: 7,
              orderable: false,
              data: 'id'
            }
          ],
          "order": [[6, "desc"]],
          createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(7)').html('<a href="' + data.urls.edit + '" class="btn btn-primary btn-sm"><i class="icon ion-md-create"></i></a>\n' +
              '                                <a href="' + data.urls.delete + '" class="btn btn-danger btn-sm delete"><i class="icon ion-md-trash"></i></a>');
          }
        });
        $('.search').keyup(function (e) {
          e.preventDefault();
          var val = $(this).val();
          if (val.length >= 3 || val === '') {
            table1.draw();
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
          table1.draw();
        });
        $('#clear-filters').click(function () {
          $('.filter').each(function () {
            var field = $(this).data('field');
            $(this).removeClass('active');
            $('#selected-' + field).html('');
            $('.filter-' + field).removeClass('active');

            $('.search').val('');
            table1.draw();
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
            text: "{{ __("You will not be able to revert this!") }}",
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
                    table1.ajax.reload();
                  } else {
                    notify(rs.data.error, 'danger');
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
              text: "{{ __("All selected users will be deleted!") }}",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              cancelButtonText: '{{ __("Cancel") }}',
              confirmButtonText: '{{ __("Yes, delete it!") }}'
            }).then((result) => {
              if (result.value) {
                axios.post("{{ route('api.user.delete-multiple') }}", {ids: ids})
                  .then(function (rs) {
                    table1.ajax.reload();
                    notify('{{ __("Notification") }}', '{{ __("All selected users were deleted!") }}', 'success');
                  })
                  .catch(function (error) {
                    notify('{{ __("Alert") }}', error.response.data.message, 'error');
                  });
              }
            });
          }
        });
      });
    </script>
@endpush
