@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-danger" id="delete-all" disabled><i class="icon ion-md-close"></i> {{ __('Delete') }}
            </button>
            <button class="btn btn-info" id="restore-all" style="display: none" disabled><i
                        class="icon ion-md-refresh"></i> {{ __('Restore') }}
            </button>
            <div class="card-tools">
                <a href="{{ route('admin.blog.category.create') }}" class="btn"><i
                            class="icon ion-md-add"></i> {{ __('blog::category.title.Create a category') }}</a>
            </div>
        </div>
        <div class="card-body">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3" id="filters">
                <a class="navbar-brand" href="#"><i class="fa fa-filter"></i> Filters</a>
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
            <table id="data-table" class="table table-striped">
                <thead class="thead-default">
                <tr>
                    <th width="60"> {{ __('Image') }}</th>
                    <th>{{ __('blog::category.labels.Title') }}</th>
                    <th width="100">{{ __('Date') }}</th>
                    <th width="50">{{ __('Status') }}</th>
                    <th width="100">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td width="80">
                            <img class="w-100" src="{{ $category->image ? $category->image->path : asset('storage/media/no-image.jpg') }}"
                                 alt="{{ $category->name }}"></td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>{{ $category->status === 1 ? __('Show') : __('Hide') }}</td>
                        <td>
                            <a href="{{ $category->getEditUrl() }}" class="btn btn-primary btn-sm" data-toggle="tooltip"
                               data-category="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>
                            <a href="{{ $category->getDeleteUrl() }}" class="btn btn-danger btn-sm delete"
                               data-toggle="tooltip" data-category="{{ __("Delete") }}"><i
                                        class="icon ion-md-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js-stack')
    <script>
      $(function () {
        $('body').on('click', '.delete', function (e) {
          e.preventDefault();
          let url = $(this).attr('href');
          swal({
            category: '{{ __("Are you sure?") }}',
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
                    categoryTable.ajax.reload();
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
        }).on('click', '.toggle-status', function () {
          var checked = $(this).prop('checked');
          axios.category('{{ route("api.blog.category.toggle_status") }}', {id: $(this).data('id')});
        });
      });
    </script>
@endpush
