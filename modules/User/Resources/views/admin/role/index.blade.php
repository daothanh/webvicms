@extends('admin::layouts.master')
@section('title')
    {{ __('user::role.title.Manage Roles') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                {{ __('user::role.title.Roles') }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.role.create') }}" class="btn">
                    <i class="fa fa-plus"></i>
                    <span>{{ __('user::role.title.Create a role') }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="table">
                    <thead class="thead-default">
                    <tr>
                        <th>{{ __('user::role.labels.Role Name') }}</th>
                        {{--<th>{{ __('Guard') }}</th>--}}
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
            var locale = "{{ config('app.locale') }}";
            // Initiate data-table
            $('#data-table').DataTable({
                autoWidth: false,
                responsive: true,
                lengthMenu: [[25, 50, 100], ['25 {{ __("Rows") }}', '50 {{ __("Rows") }}', '100 {{ __("Rows") }}']],
                language: {
                    searchPlaceholder: "{{ __('Search') }}",
                },
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('api.role.index') }}"
                },
                "columns": [
                    {"data": "name", 'searchable': true, "orderable": true},
                    // {"data": "guard_name", 'searchable': true, "orderable": false},
                    {"data": "id", 'searchable': false, "orderable": false},
                ],
                "order": [[0, "desc"]],
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(0)').html(data[locale] !== undefined && data[locale].title !== null ? data[locale].title : data.name);
                    $(row).find('td:eq(1)').html('<a href="' + data.urls.edit + '" class="btn btn-primary btn-sm" data-toggle="tooltip" title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n' +
                        '                                <a href="' + data.urls.delete + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>');
                }
            });

            // Add blue line when search is active
            $('.dataTables_filter input[type=search]').focus(function () {
                $(this).closest('.dataTables_filter').addClass('dataTables_filter--toggled');
            });

            $('.dataTables_filter input[type=search]').blur(function () {
                $(this).closest('.dataTables_filter').removeClass('dataTables_filter--toggled');
            });


            // Data table buttons
            $('body').on('click', '[data-table-action]', function (e) {
                e.preventDefault();

                var exportFormat = $(this).data('table-action');
                if (exportFormat === 'csv') {
                    $(this).closest('.dataTables_wrapper').find('.buttons-csv').trigger('click');
                }
                if (exportFormat === 'print') {
                    $(this).closest('.dataTables_wrapper').find('.buttons-print').trigger('click');
                }
                if (exportFormat === 'fullscreen') {
                    var parentCard = $(this).closest('.card');

                    if (parentCard.hasClass('card--fullscreen')) {
                        parentCard.removeClass('card--fullscreen');
                        $('body').removeClass('data-table-toggled');
                    } else {
                        parentCard.addClass('card--fullscreen')
                        $('body').addClass('data-table-toggled');
                    }
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
