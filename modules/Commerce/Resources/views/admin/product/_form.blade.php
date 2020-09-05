{{ Form::open(['route' => 'admin.commerce.product.store', 'method' => 'post', 'files' => true, 'id' => 'frm-post']) }}
@if(!empty($product))
    {{ Form::hidden('id', $product->id) }}
@endif
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ \SEO::getTitle() }}</div>
        <div class="card-tools">
            <a href="{{ route('admin.commerce.product.index') }}" class="btn">
                <i class="fa fa-arrow-left"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php
        $l = locale();
        $languages = languages();
        $hasMoreLanguages = count($languages) > 1;
        ?>
        @if($hasMoreLanguages)
            <ul class="nav nav-tabs">
                @foreach($languages as $language)
                    <li class="nav-item">
                        <a class="nav-link @if($errors->has($language->code.".title")) text-danger @endif @if($language->code === $l) active @endif"
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
                        if (!empty($product)) {
                            $translatedPage = $product->translate($lang);
                        }
                    @endphp
                    @if($hasMoreLanguages)
                        <div class="pt-3 tab-pane fade show @if($lang === $l) active @endif"
                             id="tab-{{ $lang }}" role="tabpanel" aria-labelledby="{{ $lang }}-tab">
                            @endif
                            <div class="form-group">
                                <label>{{ trans('commerce::product.labels.Title', [], $lang) }}</label>
                                {{ Form::text($lang.'[title]', old("{$lang}.title", !empty($translatedPage) ? $translatedPage->title : null), ['class' => $errors->has("{$lang}.title") ? 'form-control is-invalid title' : 'form-control title', 'id' => $lang.'-title']) }}
                                @if($errors->has("{$lang}.title"))
                                    <div class="invalid-feedback">
                                        {{ $errors->first("{$lang}.title") }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ trans('commerce::product.labels.Body', [], $lang) }}</label>
                                {{ Form::textarea($lang.'[body]', old($lang.'.body', !empty($translatedPage) ? $translatedPage->body : null), ['class' => $errors->has($lang.'.body') ? 'form-control rich-text is-invalid' : 'form-control rich-text', 'id' => $lang.'-body']) }}
                                @if($errors->has($lang.'.body'))
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $errors->first($lang.'.body') }}
                                    </div>
                                @endif
                            </div>
                            @if($hasMoreLanguages)
                        </div>
                    @endif
                @endforeach
                @if($hasMoreLanguages)
            </div>
        @endif
        <div class="form-group">
            @mediaSingle('image', $product ?? null, __('commerce::product.labels.Featured image'))
            @if($errors->has('medias_single.image'))
                <div class="invalid-feedback">
                    {{ $errors->first('single_media.image') }}
                </div>
            @endif
        </div>
        @if(!empty($categories))
            <div class="form-group">
                <label>{{ __('commerce::product.labels.Categories') }}</label>
                <?php
                $categoryIds = !empty($product) ? $product->categories->pluck('id')->toArray() : [];
                ?>
                @foreach ($categories as  $categoryId => $category)
                    @if($categoryId)
                        <div class="form-check">
                            {{ Form::checkbox('category_ids['.$categoryId.']', $categoryId, old('category_ids.'.$categoryId) || (!empty($categoryIds) && in_array($categoryId, $categoryIds)) ? true : false, ['class' => 'form-check-input', 'id' => 'category_ids_'.$categoryId]) }}
                            <label class="form-check-label" for="{{ 'category_ids_'.$categoryId}}">
                                {{ $category }}
                            </label>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
        <div class="form-group">
            <label>{{ __('commerce::product.labels.Status') }}</label>
            <?php
            $statuses = [
                1 => __('commerce::product.labels.Show'),
                0 => __('commerce::product.labels.Hide')
            ];
            ?>
            {{ Form::select('status', $statuses, old('status', !empty($product)? $product->status : 1), ['class' => 'form-control', 'id' => 'status']) }}

        </div>
    </div>
</div>
@include('core::admin.seo', ['entity' => $product ?? null])
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <a href="{{ route('admin.commerce.product.index') }}" class="btn btn-dark"><i
                        class="icon ion-md-undo"></i> {{ __('Cancel') }}</a>
            <button type="submit" class="btn btn-primary ml-3" id="save-btn"><i
                    class="icon ion-md-save"></i> {{ __('Save') }}</button>
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
