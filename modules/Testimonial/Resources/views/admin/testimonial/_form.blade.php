{{ Form::open(['route' => 'admin.testimonial.store', 'method' => 'post', 'files' => true]) }}
@if(!empty($testimonial))
    {{ Form::hidden('id', $testimonial->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    @if(!empty($testimonial))
                        {{ __('testimonial::testimonial.title.Edit a testimonial') }}
                    @else
                        {{ __('testimonial::testimonial.title.Create a testimonial') }}
                    @endif
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
                                   aria-controls="{{ $language->code }}"
                                   aria-selected="true">{{ $language->native }}</a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @endif
                        @foreach ($languages as $language)
                            @php
                                $lang = $language->code;
                                $translatedTestimonial = null;
                                if (!empty($testimonial)) {
                                    $translatedTestimonial = $testimonial->translate($lang);
                                }
                            @endphp
                            @if($hasMoreLanguages)
                                <div class="pt-3 tab-pane fade show @if($lang === $l) active @endif"
                                     id="tab-{{ $lang }}" role="tabpanel" aria-labelledby="{{ $lang }}-tab">
                                    @endif
                                    <div
                                        class="form-group m-form__group @if($errors->has("{$lang}.name")) has-danger @endif">
                                        <label>{{ __('testimonial::testimonial.labels.Name') }}</label>
                                        {{ Form::text("{$lang}[name]", old("{$lang}.name", !empty($translatedTestimonial) ? $translatedTestimonial->name : null), ['class' => $errors->has("{$lang}.name") ? 'form-control is-invalid' : 'form-control', 'id' => "{$lang}-name"]) }}
                                        <i class="form-group__bar"></i>
                                        @if($errors->has("{$lang}.name"))
                                            <div class="form-control-feedback">
                                                {{ $errors->first("{$lang}.name") }}
                                            </div>
                                        @endif
                                    </div>
                                    <div
                                        class="form-group m-form__group @if($errors->has("{$lang}.position")) has-danger @endif">
                                        <label>{{ __('testimonial::testimonial.labels.Position') }}</label>
                                        {{ Form::text("{$lang}[position]", old("{$lang}.position", !empty($translatedTestimonial) ? $translatedTestimonial->position : null), ['class' => $errors->has("{$lang}.position") ? 'form-control is-invalid' : 'form-control', 'id' => "{$lang}-position"]) }}
                                        <i class="form-group__bar"></i>
                                        @if($errors->has("{$lang}.position"))
                                            <div class="form-control-feedback">
                                                {{ $errors->first("{$lang}.position") }}
                                            </div>
                                        @endif
                                    </div>
                                    <div
                                        class="form-group m-form__group @if($errors->has("{$lang}.content")) has-danger @endif">
                                        <label>{{ __('testimonial::testimonial.labels.Content') }}</label>
                                        {{ Form::textarea("{$lang}[content]", old("{$lang}.content", !empty($translatedTestimonial) ? $translatedTestimonial->content : null), ['class' => $errors->has("{$lang}.content") ? 'form-control is-invalid' : 'rich-text', 'id' => "{$lang}-content"]) }}
                                        <i class="form-group__bar"></i>
                                        @if($errors->has("{$lang}.content"))
                                            <div class="form-control-feedback" style="display: block">
                                                {{ $errors->first("{$lang}.content") }}
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
                    @mediaSingle('photo', $testimonial ?? null, __('testimonial::testimonial.labels.Avatar'))
                    <i class="form-group__bar"></i>
                    @if($errors->has('single_media.photo'))
                        <div class="form-control-feedback">
                            {{ $errors->first('single_media.photo') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ __('testimonial::testimonial.labels.Status') }}</label>
                    <?php
                    $statuses = [
                        1 => __('testimonial::testimonial.labels.Show'),
                        0 => __('testimonial::testimonial.labels.Hide')
                    ];
                    ?>
                    {{ Form::select('status', $statuses, old('status', !empty($testimonial)? $testimonial->status : 1), ['class' => 'form-control', 'id' => 'status']) }}
                    <i class="form-group__bar"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">

            </div>
        </div>
    </div>
</div>
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__foot">
        <div class="row">
            <div class="col-md-12">
                <div class="float-left">
                    <a href="{{ route('admin.testimonial.index') }}" class="btn btn-dark"><i
                            class="la la-undo"></i> {{ __('Cancel') }}</a>
                </div>
                <div class="float-right">
                    <button class="btn btn-primary"><i class="la la-save"></i> {{ __('Save') }}</button>
                </div>
            </div>
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
            @foreach(locales() as $l)
            $('{{ '#'.$l.'-name' }}').keyup(function () {
                $("{{ '#'.$l.'-slug' }}").val(slugify($(this).val()));
            });
            @endforeach
        });
    </script>
@endpush

