@extends('admin::layouts.master')
@section('title')
    {{ __('tag::tags.title.Manage tags') }}
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{ __('tag::tags.title.List Tags') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('admin.tag.create') }}"
                           class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-plus"></i>
                                <span>{{ __('tag::tags.title.Create a tag') }}</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th width="200">{{ __('tag::tags.labels.Name') }}</th>
                        <th>{{ __('tag::tags.labels.Slug') }}</th>
                        <th width="180">{{ __('tag::tags.labels.Namespace') }}</th>
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
            $('#data-table').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[25, 50, 100], ['25 {{ __("Rows") }}', '50 {{ __("Rows") }}', '100 {{ __("Rows") }}']],
                language: {
                    searchPlaceholder: "{{ __('Search') }}",
                },
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('api.tag.index') }}"
                },
                "columns": [
                    {"data": "name", 'searchable': true, "orderable": false},
                    {"data": "slug", 'searchable': true, "orderable": true},
                    {"data": "namespace", 'searchable': true, "orderable": true},
                    {"data": "id", 'searchable': false, "orderable": false},
                ],
                "order": [[3, "desc"]],
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(3)').html('<a href="' + data.urls.edit + '" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="la la-edit"></i></a>\n' +
                        '                                <a href="' + data.urls.delete + '" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="la la-trash"></i></a>\n'
                    );
                }
            });

            $('body').on('click', '.delete', function (e) {
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
                                if (rs.data.error === false) {
                                    window.location.reload(true);
                                } else {
                                    notify(rs.data.error, 'danger');
                                }
                            });
                    }
                });
            });
        });
    </script>
@endpush
