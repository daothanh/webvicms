{{ Form::open(['route' => 'admin.slider.store', 'method' => 'slider', 'files' => true]) }}
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ \SEO::getTitle() }}</div>
        <div class="card-tools">
            <a href="{{ route('admin.slider.index') }}" class="btn">
                <i class="fa fa-arrow-left"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @if(!empty($slider))
                    {{ Form::hidden('id', $slider->id) }}
                @endif
                <div class="form-group">
                    <label>{{ __('slider::slider.labels.Title') }}</label>
                    {{ Form::text('title', old('title', !empty($slider) ? $slider->title : null), ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title']) }}
                    @if($errors->has('title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label>{{ __('slider::slider.labels.Description') }}</label>
                    {{ Form::textarea('description', old('description', !empty($slider) ? $slider->description : null), ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control rich-text', 'id' => 'description', 'rows' => 5]) }}
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                </div>
                <div class="form-group m-form__group">
                    <label>{{ __('slider::slider.labels.Status') }}</label>
                    <?php
                    $statuses = [
                        1 => __('slider::slider.labels.Show'),
                        0 => __('slider::slider.labels.Hide')
                    ];
                    ?>
                    {{ Form::select('status', $statuses, old('status', !empty($slider)? $slider->status : 1), ['class' => 'form-control', 'id' => 'status']) }}
                    <i class="form-group__bar"></i>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            <button class="btn btn-primary"><i class="far fa-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@push('js-stack')
    <script src="{{ Theme::url('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function () {
            CKEDITOR.replace('body');
            $('#title').keyup(function () {
                $('#slug').val(slugify($(this).val()));
            })
        })
    </script>
@endpush

