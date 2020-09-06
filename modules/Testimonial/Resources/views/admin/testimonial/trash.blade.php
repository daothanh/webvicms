@extends('admin::layouts.master')
@section('title')
    {{ __('Trash') }} - {{ site_name() }}
@endsection
@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        {{ __('Trash') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('admin.testimonial.index') }}"
                           class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air">
                        <span>
                            <i class="la la-list"></i>
                            <span>{{ __('testimonial::testimonial.title.List Testimonials') }}</span>
                        </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item"></li>
                    <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push"
                        m-dropdown-toggle="hover" aria-expanded="true">
                        <a href="#"
                           class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
                            Actions
                        </a>
                        <div class="m-dropdown__wrapper" style="z-index: 101;">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 36px;"></span>
                            <div class="m-dropdown__inner">
                                <div class="m-dropdown__body">
                                    <div class="m-dropdown__content">
                                        <ul class="m-nav">
                                            <li class="m-nav__section m-nav__section--first">
                                                <span class="m-nav__section-text">Export Tools</span>
                                            </li>
                                            <li class="m-nav__item">
                                                <a href="#" class="m-nav__link" id="export_print">
                                                    <i class="m-nav__link-icon la la-print"></i>
                                                    <span class="m-nav__link-text">Print</span>
                                                </a>
                                            </li>
                                            <li class="m-nav__item">
                                                <a href="#" class="m-nav__link" id="export_copy">
                                                    <i class="m-nav__link-icon la la-copy"></i>
                                                    <span class="m-nav__link-text">Copy</span>
                                                </a>
                                            </li>
                                            <li class="m-nav__item">
                                                <a href="#" class="m-nav__link" id="export_excel">
                                                    <i class="m-nav__link-icon la la-file-excel-o"></i>
                                                    <span class="m-nav__link-text">Excel</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped">
                    <thead class="thead-default">
                    <tr>
                        <th width="50">{{ __('testimonial::testimonial.labels.Featured image') }}</th>
                        <th>{{ __('testimonial::testimonial.labels.Name') }}</th>
                        <th width="200">{{ __('testimonial::testimonial.labels.Position') }}</th>
                        <th width="150">{{ __('testimonial::testimonial.labels.Content') }}</th>
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
                    "url": "{{ route('api.testimonial.index') }}?is_deleted=true"
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
                    $(row).find('td:eq(0)').html('<img width="50" src="' + (data.photo !== null && data.photo !== '' ? data.photo.small_thumb : '/storage/media/no-image.jpg') + '"/>');
                    $(row).find('td:eq(1)').html('<a target="_blank" href="' + data.urls.public + '">' + data.name + '</a>');

                    $(row).find('td:eq(5)').html('<a href="' + data.urls.restore + '" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill restore" data-toggle="m-tooltip" title="{{ __("Restore") }}"><i class="la la-mail-reply"></i></a>\n' +
                        '                                <a href="' + data.urls.forcedelete + '" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete" data-toggle="m-tooltip" title="{{ __("Delete") }}"><i class="la la-times"></i></a>\n'
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
                                    notify("{{ __('Alert') }}", rs.data.error, 'error');
                                }
                            }).catch(function (error) {
                                notify('{{ __('Alert') }}', error.response.data.message, 'error');
                            });
                    }
                });
            });
            $('body').on('click', '.restore', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                swal({
                    title: '{{ __("Are you sure?") }}',
                    text: "{{ __("It will move to the normal list!") }}",
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
                                    table.ajax.reload();
                                } else {
                                    notify('{{ __('Alert') }}', rs.data.error, 'error');
                                }
                            }).catch(function (error) {
                                notify('{{ __('Alert') }}', error.response.data.message, 'error');
                            });
                    }
                });
            });
        });
    </script>
@endpush
