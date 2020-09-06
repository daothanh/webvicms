@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                {{ $slider->title }}
            </div>
            <div class="card-tools">
                <a href="{{ route('admin.slider.item.create', ['slider' => $slider->id]) }}" class="btn"><i
                        class="icon ion-md-add"></i> {{ __('slider::slider.title.Add a slide') }}</a>
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th width="200">{{ __('Image') }}</th>
                        <th>{{ __('slider::slider.labels.Description') }}</th>
                        <th>{{ __('slider::slider.labels.Link') }}</th>
                        <th width="120">{{ __('slider::slider.labels.Status') }}</th>
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
                lengthMenu: [[25, 50, 100], ['25 {{ __("Rows") }}', '50 {{ __("Rows") }}', '100 {{ __("Rows") }}']],
                language: {
                    searchPlaceholder: "{{ __('Search') }}",
                },
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('api.slider.item.index', ['slider' => $slider->id]) }}"
                },
                "columns": [
                    {"data": "image", 'searchable': false, "orderable": false},
                    {"data": "description", 'searchable': false, "orderable": false},
                    {"data": "url", 'searchable': false, "orderable": false},
                    {"data": "status", 'searchable': false, "orderable": true},
                    {"data": "id", 'searchable': false, "orderable": false},
                ],
                "order": [[3, "desc"]],
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(0)').html('<img src="' + data.image.thumbnail + '">');
                    $(row).find('td:eq(1)').html(data.title !== null ? ('<strong>' + data.title + '</strong><br>' + (data.description !== null ? data.description : '')) : '');
                    $(row).find('td:eq(3)').html(data.status == 1 ? 'Hiển thị' : 'Ẩn');
                    $(row).find('td:eq(4)').html('<a href="' + data.urls.edit + '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n' +
                        '                                <a href="' + data.urls.delete + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>\n'
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
