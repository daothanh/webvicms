{{ Form::open(['url' => route('admin.slider.item.store', ['slider' => $slider->id]), 'method' => 'post']) }}

<div class="row">
    <div class="col-md-12">
        @if(!empty($slide))
            {{ Form::hidden('id', $slide->id) }}
        @endif
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
            <div class="form-group">
                <label>{{ trans('slider::slide.labels.Title', [], $l) }}</label>
                {{ Form::text($l.'[title]', old("{$l}.title", !empty($slide) ? $slide->title : null), ['class' => $errors->has("{$l}.title") ? 'form-control is-invalid title' : 'form-control title', 'id' => $l.'-title']) }}
                @if($errors->has("{$l}.title"))
                    <div class="invalid-feedback">
                        {{ $errors->first("{$l}.title") }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label>{{ trans('slider::slide.labels.Description', [], $l) }}</label>
                {{ Form::textarea($l.'[description]', old($l.'.description', !empty($slide) ? $slide->description : null), ['class' => $errors->has($l.'.description') ? 'form-control is-invalid' : 'form-control', 'id' => $l.'-description', 'rows' => 3]) }}
                @if($errors->has($l.'.description'))
                    <div class="invalid-feedback" style="display: block">
                        {{ $errors->first($l.'.description') }}
                    </div>
                @endif
            </div>
        </div>
        <div class="form-group m-form__group @if($errors->has("medias_single.image")) has-danger @endif">
            @mediaSingle('image', $slide ?? null, __('Image'), 'thumbnail')
            <i class="form-group__bar"></i>
            @if($errors->has('medias_single.image'))
                <div class="form-control-feedback">
                    {{ $errors->first('medias_single.image') }}
                </div>
            @endif
        </div>
        <div class="form-group m-form__group @if($errors->has("user.name")) has-danger @endif">
            <label>{{ __('slider::slider.labels.Link') }}</label>
            {{ Form::text('url', old('url', !empty($slide) ? $slide->url : null), ['class' => $errors->has('url') ? 'form-control is-invalid' : 'form-control', 'id' => 'url']) }}
            <i class="form-group__bar"></i>
            @if($errors->has('url'))
                <div class="form-control-feedback">
                    {{ $errors->first('url') }}
                </div>
            @endif
        </div>
        <div class="form-group">
            <label>{{ __('slider::slider.labels.Link target') }}</label>
            <?php
            $statuses = [
                '_self' => __('slider::slider.labels.The same window or tab'),
                '_blank' => __('slider::slider.labels.The new window or tab'),
            ];
            ?>
            {{ Form::select('url_target', $statuses, old('url_target', !empty($slide)? $slide->url_target : '_self'), ['class' => 'form-control', 'id' => 'url_target']) }}
            <i class="form-group__bar"></i>
        </div>
        <div class="form-group">
            <label>{{ __('slider::slider.labels.Status') }}</label>
            <?php
            $statuses = [
                1 => __('slider::slider.labels.Show'),
                0 => __('slider::slider.labels.Hide')
            ];
            ?>
            {{ Form::select('status', $statuses, old('status', !empty($slide)? $slide->status : 1), ['class' => 'form-control', 'id' => 'status']) }}
            <i class="form-group__bar"></i>
        </div>
        <div class="text-center">
            <button class="btn btn-primary"><i class="la la-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@push('js-stack')
    <script src="{{ Theme::url('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function () {
        })
    </script>
@endpush

