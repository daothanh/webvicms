@extends('admin::layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-danger btn-sm" id="delete-all" disabled>{{ __('Delete') }}
            </button>
            <button class="btn btn-info btn-sm" id="restore-all" style="display: none" disabled>{{ __('Restore') }}
            </button>
            <div class="card-tools">
                <a href="{{ route('admin.commerce.category.create') }}" class="btn"><i
                        class="icon ion-md-add"></i> {{ __('commerce::category.title.Create a category') }}</a>
            </div>
        </div>
        <div class="card-body">
            <table id="data-table1" class="table table-striped">
                <thead class="thead-default">
                <tr>
                    <th width="60"> {{ __('commerce::category.labels.Image') }}</th>
                    <th>{{ __('commerce::category.labels.Title') }}</th>
                    <th width="200">{{ __('commerce::category.labels.Excerpt') }}</th>
                    <th width="200">{{ __('Slug') }}</th>
                    <th width="100">{{ __('commerce::category.labels.Status') }}</th>
                    <th width="120">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @if(count($categories))
                    @foreach($categories as $category)
                        <tr>
                            <td width="80">
                                <img class="w-100"
                                     src="{{ $category->image ? $category->image->path : asset('storage/media/no-image.jpg') }}"
                                     alt="{{ $category->name }}"></td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->excerpt }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                <input data-id="{{ $category->id }}" class="toggle-status" type="checkbox" name="status"
                                       @if($category->status === 1) checked @endif data-bootstrap-switch>
                            </td>
                            <td>
                                <a href="{{ $category->getEditUrl() }}" class="btn btn-primary btn-sm"
                                   data-toggle="tooltip"
                                   data-category="{{ __("Edit") }}"><i class="icon ion-md-create"></i></a>
                                <a href="{{ $category->getDeleteUrl() }}" class="btn btn-danger btn-sm delete"
                                   data-toggle="tooltip" data-category="{{ __("Delete") }}"><i
                                        class="icon ion-md-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">
                            <svg width="64" height="41" viewBox="0 0 64 41" xmlns="http://www.w3.org/2000/svg">
                                <g transform="translate(0 1)" fill="none" fillRule="evenodd">
                                    <ellipse fill="#F5F5F5" cx="32" cy="33" rx="32" ry="7"></ellipse>
                                    <g fillRule="nonzero" stroke="#D9D9D9">
                                        <path
                                            d="M55 12.76L44.854 1.258C44.367.474 43.656 0 42.907 0H21.093c-.749 0-1.46.474-1.947 1.257L9 12.761V22h46v-9.24z"></path>
                                        <path
                                            d="M41.613 15.931c0-1.605.994-2.93 2.227-2.931H55v18.137C55 33.26 53.68 35 52.05 35h-40.1C10.32 35 9 33.259 9 31.137V13h11.16c1.233 0 2.227 1.323 2.227 2.928v.022c0 1.605 1.005 2.901 2.237 2.901h14.752c1.232 0 2.237-1.308 2.237-2.913v-.007z"
                                            fill="#FAFAFA"></path>
                                    </g>
                                </g>
                            </svg>
                            <br>
                            <div>Chưa có danh mục</div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js-stack')
    <script src="{{ Theme::url('js/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function () {
            $('.toggle-status').bootstrapSwitch({
                onColor: 'primary',
                offColor: 'danger',
                onText: '{{ __('blog::category.labels.Show') }}',
                offText: '{{ __('blog::category.labels.Hide') }}',
                onSwitchChange: function onSwitchChange() {
                    axios.post('{{ route("api.blog.category.toggle_status") }}', {id: $(this).data('id')});
                }
            });
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
            });
        });
    </script>

@endpush
