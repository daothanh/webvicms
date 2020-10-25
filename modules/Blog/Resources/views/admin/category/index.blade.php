@extends('admin::layouts.master')

@section('content')
    <div class="row">
        <div class="col-4">
            @include("blog::admin.category._form")
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ \SEO::getTitle() }}</div>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-default">
                        <tr>
                            <th width="60"> {{ __('blog::category.labels.Image') }}</th>
                            <th>{{ __('blog::category.labels.Title') }}</th>
                            <th width="200">{{ __('blog::category.labels.Excerpt') }}</th>
                            <th width="200">{{ __('Slug') }}</th>
                            <th width="120">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                    </table>
                    <ul class="categories">
                        @if(count($categories))
                            <?php
                            $prevCategory = null;
                            ?>
                            @foreach($categories as $k => $category)


                                <li data-id="{{ $category->id }}" data-pid="{{ $category->pid }}"
                                    class="depth-{{ $category->depth }}">
                                    <div>
                                        <div class="image">
                                            <img class="w-100"
                                                 src="{{ $category->image ? $category->image->path : asset('uploads/media/no-image.jpg') }}"
                                                 alt="{{ $category->name }}"></div>
                                        <div class="name">{{ $category->name }}</div>
                                        <div class="excerpt">{{ $category->excerpt }}</div>
                                        <div class="slug">{{ $category->slug }}</div>
                                        <div class="actions">
                                            <a href="?id={{ $category->id }}" class="btn btn-primary btn-sm"
                                               data-toggle="tooltip"
                                               data-category="{{ __("Edit") }}"><i
                                                    class="icon ion-md-create"></i></a>
                                            <a href="{{ $category->getDeleteUrl() }}"
                                               class="btn btn-danger btn-sm delete"
                                               data-toggle="tooltip" data-category="{{ __("Delete") }}"><i
                                                    class="icon ion-md-trash"></i></a>
                                        </div>
                                    </div>
                                    <?php $nextCategory = !empty($categories[$k + 1]) ? $categories[$k + 1] : null ?>
                                    @if($nextCategory !== null && $category->depth < $nextCategory->depth)
                                        <ul class="nested">
                                            @endif
                                            @if((!empty($nextCategory) && $category->depth > $nextCategory->depth))

                                        </ul>
                                </li>
                                @elseif($nextCategory === null || ($nextCategory->depth === $category->depth))
                                    <ul class="nested"></ul>
                                    </li>
                                @endif

                                <?php
                                $prevCategory = $category;
                                ?>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css-stack')
    {!! Theme::css('js/jquery-ui/jquery-ui.css') !!}
    <style>
        .categories {
            display: block;
            width: 100%;
            padding-left: 0;
        }

        .categories li {
            list-style: none;
            width: 100%;
        }

        .categories li.placeholder {
            position: relative;
            margin: 0;
            padding: 0;
            border: none;
        }

        .categories li.placeholder:before {
            position: absolute;
            content: "";
            width: 0;
            height: 0;
            margin-top: -5px;
            left: -5px;
            top: -4px;
            border: 5px solid transparent;
            border-left-color: red;
            border-right: none;
        }

        .categories li > div {
            text-align: left;
            display: grid;
            grid-template-areas: "a b c d e";
            grid-template-columns: 1fr 3fr 3fr 3fr 1fr;
            grid-template-rows: 1fr;
            margin: 5px 0;
            width: 100%;
        }

        .categories li div > div {
            display: table-cell;
        }

        .categories li ul {
            padding-left: 15px;
        }

        .categories li img {
            max-width: 50px;
        }

        body.dragging, body.dragging * {
            cursor: move !important;
        }

        .dragged {
            position: absolute;
            opacity: 0.5;
            z-index: 2000;
        }

        ul.categories li.placeholder {
            position: relative;
        }

        ul.categories li.placeholder:before {
            position: absolute;
        }
    </style>
@endpush
@push('js-stack')
    {!! Theme::js('js/jquery-sortable.js') !!}
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
                                notify('{{ __("Notification") }}', '{{ __("It was moved to the trash!") }}', 'success');
                                setTimeout(function () {
                                    window.location.reload(true);
                                }, 1000)
                            })
                            .catch(function (error) {
                                notify('{{ __("Alert") }}', error.response.data.message, 'error');
                            });
                    }
                });
            });

            function updateItems(elements, pid) {
                pid = pid || 0
                let items = []
                elements.each((k, item) => {
                    if ($(item).data('id')) {
                        items.push({id: $(item).data('id'), order: items.length + 1, pid: pid})
                        if ($(item).find('.nested>li')) {
                            updateItems($(item).find('.nested>li'), $(item).data('id'));
                        }
                    }
                });
                axios.post("{{ route('api.blog.category.update_position') }}", {items: items})
                return items;
            }

            var adjustment;
            $("ul.categories").sortable({
                group: 'nested',
                pullPlaceholder: true,

                // animation on drop
                onDrop: function ($item, container, _super) {
                    var $clonedItem = $('<li/>').css({height: 0});
                    $item.before($clonedItem);
                    $clonedItem.animate({
                        'height': $item.height()
                    });

                    $item.animate($clonedItem.position(), function () {
                        $clonedItem.detach();
                        _super($item, container);
                    });
                    updateItems($("ul.categories>li"));
                },
                // set $item relative to cursor position
                onDragStart: function ($item, container, _super) {
                    var offset = $item.offset(),
                        pointer = container.rootGroup.pointer;

                    adjustment = {
                        left: pointer.left - offset.left,
                        top: pointer.top - offset.top
                    };
                    _super($item, container);
                },

                onDrag: function ($item, position) {
                    $item.css({
                        left: position.left - adjustment.left,
                        top: position.top - adjustment.top
                    });
                }
            });
        });
    </script>

@endpush
