{{ Form::open(['route' => !empty($tag) ? 'admin.tag.update' : 'admin.tag.store', 'method' => 'post', 'files' => true]) }}
<div class="row">
    <div class="col-md-12">
        @if(!empty($tag))
            {{ Form::hidden('id', $tag->id) }}
        @endif
        <div class="form-group @if($errors->has("name")) has-danger @endif">
            <label>{{ __('tag::tags.labels.Name') }}</label>
            {{ Form::text('name', old('name', !empty($tag) ? $tag->name : null), ['class' => $errors->has('name') ? 'form-control is-invalid' : 'form-control', 'id' => 'name']) }}
            <i class="form-group__bar"></i>
            @if($errors->has('name'))
                <div class="form-control-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>
        <div class="form-group @if($errors->has("slug")) has-danger @endif">
            <label>{{ __('tag::tags.labels.Slug') }}</label>
            {{ Form::text('slug', old('slug', !empty($tag) ? $tag->slug : null), ['class' => $errors->has('slug') ? 'form-control is-invalid' : 'form-control', 'id' => 'slug']) }}
            <i class="form-group__bar"></i>
            @if($errors->has('slug'))
                <div class="form-control-feedback">
                    {{ $errors->first('slug') }}
                </div>
            @endif
        </div>
        <div class="form-group @if($errors->has("slug")) has-danger @endif">
            <label>{{ __('tag::tags.labels.Namespace') }}</label>
            {{ Form::text('namespace', old('namespace', !empty($tag) ? $tag->namespace : null), ['class' => $errors->has('namespace') ? 'form-control is-invalid' : 'form-control', 'id' => 'namespace']) }}
            <i class="form-group__bar"></i>
            @if($errors->has('namespace'))
                <div class="form-control-feedback">
                    {{ $errors->first('namespace') }}
                </div>
            @endif
        </div>
        <div class="text-center">
            <button class="btn btn-primary"><i class="zmdi zmdi-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
@push('js-stack')
    <script>
        $(function () {
            $('#name').keyup(function () {
                $('#slug').val(slugify($(this).val()));
            })
        })
    </script>
@endpush

