@extends('admin::layouts.master')
@section('title')
    {{ __('slider::slider.title.Manage sliders') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-danger" id="delete-all" disabled><i class="icon ion-md-close"></i> {{ __('Delete') }}
            </button>
            <button class="btn btn-info" id="restore-all" style="display: none" disabled><i
                    class="icon ion-md-refresh"></i> {{ __('Restore') }}
            </button>
            <div class="card-tools">
                <a href="{{ route('admin.slider.create') }}" class="btn"><i
                        class="icon ion-md-add"></i> {{ __('slider::slider.title.Create a slider') }}</a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th colspan="1" width="28" data-col="0">
                            <input type="checkbox" id="mass-select-all" name="mass-select-all">
                        </th>
                        <th width="300">{{ __('slider::slider.labels.Title') }}</th>
                        <th width="120">{{ __('Created At') }}</th>
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

            $('#data-table').DataTable({
                autoWidth: false,
                responsive: true,
                bFilter: false,
                lengthChange: false,
                pageLength: 25,
                serverSide: true,
                ajax: {
                    url: "{{ route('api.slider.index') }}",
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
                            return '<a href="' + row.urls.slides + '">' + row.title + '</a>'
                        }
                    },
                    {
                        targets: 2,
                        orderable: true,
                        data: "created_at"
                    },
                    {
                        targets: 3,
                        orderable: false,
                        data: function (row, type, val, meta) {
                            var buttons = '';
                            buttons += '<a href="' + row.urls.edit + '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n';
                            buttons += '<a href="' + row.urls.delete + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>\n';
                            return buttons;
                        }
                    }
                ],
                "order": [[2, "desc"]]
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
