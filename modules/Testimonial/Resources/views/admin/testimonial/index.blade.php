@extends('admin::layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ __('testimonial::testimonial.title.List Testimonials') }}</div>
            <div class="card-tools">
                <a href="{{ route('admin.testimonial.create') }}" class="btn"><i
                        class="icon ion-md-add"></i> {{ __('testimonial::testimonial.title.Create a testimonial') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th width="50">{{ __('testimonial::testimonial.labels.Avatar') }}</th>
                        <th width="200">{{ __('testimonial::testimonial.labels.Name') }}</th>
                        <th width="200">{{ __('testimonial::testimonial.labels.Position') }}</th>
                        <th>{{ __('testimonial::testimonial.labels.Content') }}</th>
                        <th width="150">{{ __('Created At') }}</th>
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
            var table = $('#data-table').DataTable({
                buttons: [
                    'print',
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                ],
                autoWidth: false,
                responsive: true,
                lengthMenu: [[25, 50, 100], ['25 {{ __("Rows") }}', '50 {{ __("Rows") }}', '100 {{ __("Rows") }}']],
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('api.testimonial.index') }}"
                },
                "columns": [
                    {"data": "photo", 'searchable': false, "orderable": false},
                    {"data": "name", 'searchable': true, "orderable": true},
                    {"data": "position", 'searchable': false, "orderable": true},
                    {"data": "content", 'searchable': false, "orderable": false},
                    {"data": "created_at", 'searchable': false, "orderable": true},
                    {"data": "id", 'searchable': false, "orderable": false},
                ],
                "order": [[4, "desc"]],
                createdRow: function (row, data, dataIndex) {
                    $(row).find('td:eq(0)').html('<img width="50" src="' + (data.photo !== null && data.photo !== '' ? data.photo.thumbnail : '/uploads/media/no-image.jpg') + '"/>');
                    $(row).find('td:eq(5)').html('<a href="' + data.urls.edit + '" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>\n' +
                        '                                <a href="' + data.urls.delete + '" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-title="{{ __("Delete") }}"><i class="icon ion-md-trash"></i></a>\n'
                    );
                }
            });
            $('#export_print').on('click', function (e) {
                e.preventDefault();
                table.button(0).trigger();
            });

            $('#export_copy').on('click', function (e) {
                e.preventDefault();
                table.button(1).trigger();
            });

            $('#export_excel').on('click', function (e) {
                e.preventDefault();
                table.button(2).trigger();
            });
            $('body').on('click', '.delete', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                swal({
                    title: '{{ __("Are you sure?") }}',
                    text: "{{ __("The testimonial will be move to the trash!") }}",
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
                                    table.ajax.reload();
                                } else {
                                    notify('__("Alert")', rs.data.error, 'error');
                                }
                            });
                    }
                });
            });
        });
    </script>
@endpush
