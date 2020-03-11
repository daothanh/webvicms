{{ Form::open(['route' => 'admin.blog.category.store', 'method' => 'post', 'files' => true, 'id' => 'frm-category']) }}
@if(!empty($category))
    {{ Form::hidden('id', $category->id) }}
@endif
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="{{ route('admin.blog.category.index') }}" class="btn">
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
                            <div class="form-group">
                                <label>{{ trans('blog::category.labels.Title', [], $lang) }}</label>
                                {{ Form::text($lang.'[name]', old("{$lang}.name", !empty($translatedPage) ? $translatedPage->name : null), ['class' => $errors->has("{$lang}.name") ? 'form-control is-invalid name' : 'form-control name', 'id' => $lang.'-name']) }}
                                @if($errors->has("{$lang}.name"))
                                    <div class="invalid-feedback">
                                        {{ $errors->first("{$lang}.name") }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>{{ trans('blog::category.labels.Body', [], $lang) }}</label>
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
            @mediaSingle('image', $category ?? null, __('blog::category.labels.Image'))
            @if($errors->has('medias_single.image'))
                <div class="invalid-feedback">
                    {{ $errors->first('single_media.image') }}
                </div>
            @endif
        </div>
        <div class="form-group">
            <label>{{ __('blog::category.labels.Order') }}</label>
            {{ Form::text('order', old('order', !empty($category)? $category->order : 0), ['class' => 'form-control', 'id' => 'status']) }}
        </div>
        <div class="form-group">
            <label>{{ __('blog::category.labels.Parent') }}</label>
            {{ Form::select('pid', $categories, old('pid', !empty($category)? $category->pid : 1), ['class' => 'form-control', 'id' => 'status']) }}
        </div>
        <div class="form-group">
            <label>{{ __('blog::category.labels.Status') }}</label>
            <?php
            $statuses = [
                1 => __('blog::category.labels.Show'),
                0 => __('blog::category.labels.Hide')
            ];
            ?>
            {{ Form::select('status', $statuses, old('status', !empty($category)? $category->status : 1), ['class' => 'form-control', 'id' => 'status']) }}

        </div>
    </div>
</div>
@include('core::admin.seo', ['entity' => $category ?? null])
<div class="row">
    <div class="col-md-12">
        <div class="float-left">
            <a href="{{ route('admin.blog.category.index') }}" class="btn btn-dark"><i
                        class="icon ion-md-undo"></i> {{ __('Cancel') }}</a>
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary" id="save-btn"><i
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
