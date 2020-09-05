{{ Form::open(['route' => 'admin.blog.post.store', 'method' => 'post', 'files' => true, 'id' => 'frm-post']) }}

<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <div class="card-title">{{ \SEO::getTitle() }}</div>
            <a href="{{ route('admin.blog.post.index') }}" class="btn">
                <i class="fa fa-arrow-left"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php
        $l = locale();
        $language = get_language_by_code($l);
        ?>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link @if($errors->has($language->code.".title")) text-danger @endif @if($language->code === $l) active @endif"
                   id="{{ $language->code }}-tab"
                   data-toggle="tab" href="#tab-{{ $language->code }}" role="tab"
                   aria-controls="{{ $language->code }}" aria-selected="true">{{ $language->native }}</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            @php
                $translatedPage = null;
                if (!empty($post)) {
                    $translatedPage = $post->translate($l);
                }
            @endphp
            <div class="pt-3 tab-pane fade show active"
                 id="tab-{{ $l }}" role="tabpanel" aria-labelledby="{{ $l }}-tab">
                <div class="form-group">
                    <label>{{ trans('blog::post.labels.Title', [], $l) }} <span class="text-danger">*</span></label>
                    {{ Form::text($l.'[title]', old("{$l}.title", !empty($translatedPage) ? $translatedPage->title : null), ['class' => $errors->has("{$l}.title") ? 'form-control is-invalid title' : 'form-control title', 'id' => $l.'-title']) }}
                    @if($errors->has("{$l}.title"))
                        <div class="invalid-feedback">
                            {{ $errors->first("{$l}.title") }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ trans('blog::post.labels.Body', [], $l) }} <span class="text-danger">*</span></label>
                    {{ Form::textarea($l.'[body]', old($l.'.body', !empty($translatedPage) ? $translatedPage->body : null), ['class' => $errors->has($l.'.body') ? 'form-control rich-text is-invalid' : 'form-control rich-text', 'id' => $l.'-body']) }}
                    @if($errors->has($l.'.body'))
                        <div class="invalid-feedback" style="display: block">
                            {{ $errors->first($l.'.body') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ trans('blog::post.labels.Excerpt', [], $l) }}</label>
                    {{ Form::textarea($l.'[excerpt]', old($l.'.excerpt', !empty($translatedPage) ? $translatedPage->excerpt : null), ['class' => $errors->has($l.'.excerpt') ? 'form-control is-invalid' : 'form-control', 'id' => $l.'-excerpt', 'rows' => 3]) }}
                    @if($errors->has($l.'.excerpt'))
                        <div class="invalid-feedback" style="display: block">
                            {{ $errors->first($l.'.excerpt') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ trans('blog::post.labels.Quote', [], $l) }}</label>
                    {{ Form::textarea($l.'[quote]', old($l.'.quote', !empty($translatedPage) ? $translatedPage->quote : null), ['class' => $errors->has($l.'.quote') ? 'form-control is-invalid' : 'form-control', 'id' => $l.'-quote', 'rows' => 3]) }}
                    @if($errors->has($l.'.quote'))
                        <div class="invalid-feedback" style="display: block">
                            {{ $errors->first($l.'.quote') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(!empty($categories))
            <div class="form-group">
                <label>{{ __('blog::category.title.Categories') }} <span class="text-danger">*</span></label>
                <div class="m-checkbox-list">
                    @foreach($categories as $c)
                        <div class="form-check">
                            <input name="category_ids[]" value="{{ $c->id }}" type="checkbox"
                                   id="category_ids_{{ $c->id }}"
                                   @if(in_array($c->id, old('category_ids', [])) || (!empty($post) && in_array($c->id, $post->categories->pluck('id')->toArray()))) checked="checked" @endif>
                            <label class="form-check-label" for="category_ids_{{ $c->id }}">
                                {{ $c->name }}
                            </label>
                        </div>
                    @endforeach
                    <i class="form-group__bar"></i>
                    @if($errors->has('post_category_id'))
                        <div class="form-control-feedback">
                            {{ $errors->first('post_category_id') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="form-group">
            @mediaSingle('image', $post ?? null, __('blog::post.labels.Featured image'), 'thumbnail', null, true)
        </div>
        <div class="form-group">
            <label>{{ __('blog::post.labels.Status') }}</label>
            <?php
            $statuses = [
                1 => __('blog::post.labels.Show'),
                0 => __('blog::post.labels.Hide')
            ];
            ?>
            {{ Form::select('status', $statuses, old('status', !empty($post)? $post->status : 1), ['class' => 'form-control', 'id' => 'status']) }}

        </div>
    </div>
</div>
@include('core::admin.seo', ['entity' => $post ?? null])
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <a href="{{ route('admin.blog.post.index') }}" class="btn btn-dark"><i
                    class="icon ion-md-undo"></i> {{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary ml-3" id="save-btn"><i
                    class="icon ion-md-save"></i> {{ __('Duplicate') }}</button>
        </div>
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
        });
    </script>
@endpush
