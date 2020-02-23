<div class="form-group media-directive">
    {!! Form::label($zone, $name) !!}
    <div class="clearfix"></div>

    <button type="button" class="btn btn-primary btn-browse"
       onclick="openMediaWindowSingle(event, '{{ $zone }}');" <?php echo (isset($media->path)) ? 'style="display:none;"' : '' ?>>
        <i class="la la-image"></i>
        {{ trans('media::media.Browse') }}
    </button>

    <div class="clearfix"></div>

    <ul id="thumbnails" class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
        <?php if (isset($media->path)): ?>
        <li data-id="{{ $media->id }}">
            <div class="preview">
                <button class="jsRemoveSimpleLink" href="#" title="{{ __('Remove') }}">
                    <i class="la la-remove"></i>
                </button>
                <div class="thumbnail">
                    <div class="centered">
            <?php if ($media->media_type === 'image'): ?>
            <img src="{{ Imagy::getThumbnail($media->path, (isset($thumbnailSize) ? $thumbnailSize : 'm')) }}"
                 alt="{{ $media->alt_attribute }}"/>
            <?php elseif ($media->media_type === 'video'): ?>
            <video src="{{ $media->path }}" controls width="320"></video>
            <?php elseif ($media->media_type === 'audio'): ?>
            <audio controls>
                <source src="{{ $media->path }}" type="{{ $media->mimetype }}">
            </audio>
            <?php else: ?>
            <div class="file">
            <i class="la la-file" style="font-size: 50px;"></i> <br>
            {{ $media->filename }}
            </div>
            <?php endif; ?>

                    </div>
                </div>
            </div>
        </li>
            <input type="hidden" name="medias_single[{{ $zone }}]" value="{{ $media->id }}">
        <?php else: ?>
        <input type="hidden" name="medias_single[{{ $zone }}]" value="">
        <?php endif; ?>

    </ul>
</div>
@push('js-stack')
    <script src="{{ Theme::url('js/media.js') }}"></script>
@endpush
