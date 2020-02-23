{{ Form::open(['route' => 'admin.media.update', 'method' => 'post', 'files' => true]) }}
<div class="row">
    <div class="col-md-12">
        @if(!empty($media))
            {{ Form::hidden('id', $media->id) }}
        @endif
        <div class="form-group">
            <label>{{ __('media::media.table.title') }}</label>
            {{ Form::text('title', old('title', !empty($media) ? $media->title : null), ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'id' => 'title']) }}
            <i class="form-group__bar"></i>
            @if($errors->has('title'))
                <div class="form-control-feedback">
                    {{ $errors->first('title') }}
                </div>
            @endif
        </div>
        <div class="form-group">
            <label>{{ __('media::media.table.description') }}</label>
            {{ Form::textarea('description', old('description', !empty($media) ? $media->description : null), ['class' => $errors->has('description') ? 'form-control is-invalid' : 'form-control rich-text', 'id' => 'description', 'rows' => 5]) }}
            <i class="form-group__bar"></i>
            @if($errors->has('description'))
                <div class="form-control-feedback">
                    {{ $errors->first('description') }}
                </div>
            @endif
        </div>
            <div class="form-group">
                <table class="table table-bordered">
                    <tr>
                        <th>{{ __('media::media.table.filename') }}</th>
                        <td>{{ $media->filename }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('media::media.table.path') }}</th>
                        <td>{{ asset($media->path) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('media::media.table.file size') }}</th>
                        <td>{{ \Modules\Media\Image\Helpers\FileHelper::filesize($media->filesize) }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('media::media.table.type file') }}</th>
                        <td>{{ $media->extension }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Uploaded at') }}</th>
                        <td>{{ $media->created_at }}</td>
                    </tr>
                </table>
            </div>
        @if($thumbnails)
            <div class="form-group">
                @foreach($thumbnails as $thumbnail)
                    <figure>
                        <img src="{{ $thumbnail['path'] }}" alt="{{ $media->title }}">
                        {{ $thumbnail['name'] }}
                    </figure>
                @endforeach
            </div>
        @endif
        <div class="text-center">
            <button class="btn btn-primary"><i class="icon ion-md-save"></i> {{ __('Save') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}

