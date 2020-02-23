<?php
use Modules\Media\Entities\Media;

/**
 * @var String $zone
 * @var String $name
 * @var Media $media
 */
?>
<div class="form-group media-directive">
    {!! Form::label($zone, $name) !!}
    <div class="clearfix"></div>
    <button type="button" class="btn btn-primary btn-upload" onclick="openMediaWindowMultiple(event, '{{ $zone }}')">
        <i class="la la-image"></i>
        {{ trans('media::media.Browse') }}
    </button>
    <div class="clearfix"></div>
    <ul id="thumbnails" class="jsThumbnailImageWrapper jsMultipleThumbnailWrapper ">
        <?php
        if (isset($media) && !$media->isEmpty()) {
            $order_list = [];
            foreach ($media as $file) :
                $order_list[$zone][] = $file->id; ?>
        <li data-id="{{ $file->id }}">
            <div class="preview">
                <button class="jsRemoveLink" data-id="{{ $file->id }}"><i class="la la-remove"></i></button>
                <div class="thumbnail">
                    <div class="centered">
                            <?php if ($file->media_type === 'image') : ?>
                        <img
                            src="{{ Imagy::getThumbnail($file->path, (isset($thumbnailSize) ? $thumbnailSize : 'm')) }}"
                            alt="{{ $file->alt_attribute }}"/>
                            <?php elseif ($file->media_type === 'video') : ?>
                        <video src="{{ $file->path }}" controls width="320"></video>
                            <?php elseif ($file->media_type === 'audio') : ?>
                        <audio controls>
                            <source src="{{ $file->path }}" type="{{ $file->mimetype }}">
                        </audio>
                            <?php else : ?>
                        <div class="file"><i class="fa fa-file" style="font-size: 50px;"></i><br>{{ $file->filename }}</div>
                            <?php endif; ?>
                        <a class="jsRemoveLink" href="#" data-id="{{ $file->pivot->id }}"
                           title="{{ __('Remove') }}">
                            <i class="fa fa-times-circle"></i>
                        </a>
                        <input type="hidden" name="medias_multi[{{ $zone }}][files][]" value="{{ $file->id }}">
                    </div>
                </div>
            </div>
        </li>
            <?php endforeach; ?>
        <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="{{ implode(',', $order_list[$zone]) }}"
               class="orders" id="orders">
        <?php } else { ?>
        <input type="hidden" name="medias_multi[{{ $zone }}][orders]" value="" class="orders" id="orders">
        <?php } ?>
    </ul>
</div>
@push('js-stack')
    <script src="{{ Theme::url('js/media.js') }}"></script>
@endpush
