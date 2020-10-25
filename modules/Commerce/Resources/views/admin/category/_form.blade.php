{{ Form::open(['route' => 'admin.commerce.category.store', 'method' => 'post', 'files' => true, 'id' => 'frm-category']) }}
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ __('commerce::category.title.Create a category') }}</div>
    </div>
    <div class="card-body">
        @if(!empty($category))
            {{ Form::hidden('id', $category->id) }}
        @endif
        <?php
        $l = locale();
        $languages = languages();
        $hasMoreLanguages = count($languages) > 1;
        ?>
        @if($hasMoreLanguages)
            <ul class="nav nav-tabs">
                @foreach($languages as $language)
                    <li class="nav-item">
                        <a class="nav-link @if($errors->has($language->code.".name")) text-danger @endif @if($language->code === $l) active @endif"
                           id="{{ $language->code }}-tab"
                           data-toggle="tab" href="#tab-{{ $language->code }}" role="tab"
                           aria-controls="{{ $language->code }}" aria-selected="true">{{ $language->native }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent">
                @endif
                @foreach($languages as $language)
                    @php
                        $lang = $language->code;
                        $translatedPage = null;
                        if (!empty($category)) {
                            $translatedPage = $category->translate($lang);
                        }
                    @endphp
                    @if($hasMoreLanguages)
                        <div class="pt-3 tab-pane fade show @if($lang === $l) active @endif"
                             id="tab-{{ $lang }}" role="tabpanel" aria-labelledby="{{ $lang }}-tab">
                            @endif
                            <div class="form-group" id="{{ $lang.'_name' }}">
                                <label>{{ trans('commerce::category.labels.Title', [], $lang) }}</label>
                                {{ Form::text($lang.'[name]', old("{$lang}.name", !empty($translatedPage) ? $translatedPage->name : null), ['class' => $errors->has("{$lang}.name") ? 'form-control is-invalid name' : 'form-control name', 'id' => $lang.'-name']) }}
                                <div class="invalid-feedback" style="display: none"></div>
                            </div>
                            <div class="form-group" id="{{ $lang.'_excerpt' }}">
                                <label>{{ trans('commerce::category.labels.Excerpt', [], $lang) }}</label>
                                {{ Form::textarea($lang.'[excerpt]', old($lang.'.excerpt', !empty($translatedPage) ? $translatedPage->excerpt : null), ['class' => $errors->has($lang.'.excerpt') ? 'form-control is-invalid' : 'form-control', 'rows' => 3, 'id' => $lang.'-excerpt']) }}
                            </div>
                            {{--
                            <div class="form-group">
                                <label>{{ trans('commerce::category.labels.Body', [], $lang) }}</label>
                                {{ Form::textarea($lang.'[body]', old($lang.'.body', !empty($translatedPage) ? $translatedPage->body : null), ['class' => $errors->has($lang.'.body') ? 'form-control rich-text is-invalid' : 'form-control rich-text', 'id' => $lang.'-body']) }}
                                @if($errors->has($lang.'.body'))
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $errors->first($lang.'.body') }}
                                    </div>
                                @endif
                            </div>
                            --}}
                            @if($hasMoreLanguages)
                        </div>
                    @endif
                @endforeach
                @if($hasMoreLanguages)
            </div>
        @endif
        <div class="form-group">
            <label>{{ __('commerce::category.labels.Parent') }}</label>
            <?php
            $optionAttrs = [];
            if(!empty($category)) {
                $optionAttrs[$category->id] =  ['disabled' => 'disabled'];
                foreach($categories as $c) {
                    if ($c->pid === $category->id) {
                        $optionAttrs[$c->id] =  ['disabled' => 'disabled'];
                    }
                }

            }
            ?>
            {{ Form::select('pid', ['0' => '-- '.__('Select').' --'] + collect($categories)->pluck('name', 'id')->toArray(), old('pid', !empty($category)? $category->pid : '0'), ['class' => 'form-control', 'id' => 'pid'], $optionAttrs) }}
        </div>
        <div class="form-group">
            @mediaSingle('image', $category ?? null, __('commerce::category.labels.Featured image'))
            @if($errors->has('medias_single.image'))
                <div class="invalid-feedback">
                    {{ $errors->first('single_media.image') }}
                </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary" id="save-btn">
            <i class="icon ion-md-add"></i> {{ !empty($category) ? __('Update category') : __('Add new category') }}
        </button>
    </div>
</div>
{{ Form::close() }}
@push('js-stack')
    <script src="{{ Theme::url('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function () {
            $('.rich-text').each(function () {
                CKEDITOR.replace(this.id, {});
            });
            $('#frm-category').on('submit', function (event) {
                event.preventDefault();
                $('#save-btn').trigger('click');
            })
            $('#save-btn').click(function () {
                const data = $('#frm-category').serializeArray();
                const form = new FormData;
                for (let i = 0; i < data.length; i++) {
                    form.append(data[i].name, data[i].value);
                }
                axios.post("{{ route('api.commerce.category.store') }}", form)
                    .then(function (rs) {
                        window.location.href = "{{ route('admin.commerce.category.index') }}";
                    })
                    .catch(function (error) {
                        if (error.response.status === 400) {
                            const errorData = error.response.data;
                            for (const k in errorData) {
                                const ele = $('#' + k.replace('.', '_'))
                                if (ele.length > 0) {
                                    ele.find('.form-control').addClass('is-invalid')
                                    ele.find('.invalid-feedback').html(errorData[k][0]).show()
                                }
                            }
                        }
                    })
                    .finally(function () {

                    })
            })
        });
    </script>
@endpush
