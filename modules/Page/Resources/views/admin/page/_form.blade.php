<?php
/**
 * @var Modules\Page\Entities\Page $page
 */
?>
{{ Form::open(['route' => 'admin.page.store', 'method' => 'post', 'files' => true, 'id' => 'frm-page']) }}
@if(!empty($page))
    {{ Form::hidden('id', $page->id) }}
@endif
<div class="card">
    <div class="card-header">
        <div class="card-tools">
            <a href="{{ route('admin.page.index') }}" class="btn">
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
                if (!empty($page)) {
                    $translatedPage = $page->translate($l);
                }
            @endphp
            <div class="pt-3 tab-pane fade show active"
                 id="tab-{{ $l }}" role="tabpanel" aria-labelledby="{{ $l }}-tab">
                <div class="form-group">
                    <label>{{ trans('page::page.labels.Title', [], $l) }}</label>
                    {{ Form::text($l.'[title]', old("{$l}.title", !empty($translatedPage) ? $translatedPage->title : null), ['class' => $errors->has("{$l}.title") ? 'form-control is-invalid title' : 'form-control title', 'id' => $l.'-title']) }}
                    @if($errors->has("{$l}.title"))
                        <div class="invalid-feedback">
                            {{ $errors->first("{$l}.title") }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ trans('page::page.labels.Url', [], $l) }}</label>
                    {{ Form::text($l.'[slug]', old("{$l}.slug", !empty($translatedPage) ? $translatedPage->slug : null), ['class' => $errors->has("{$l}.slug") ? 'form-control is-invalid slug' : 'form-control slug', 'id' => $l.'-slug']) }}
                    @if($errors->has("{$l}.slug"))
                        <div class="invalid-feedback">
                            {{ $errors->first("{$l}.slug") }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label>{{ trans('page::page.labels.Description', [], $l) }}</label>
                    {{ Form::textarea($l.'[description]', old($l.'.description', !empty($translatedPage) ? $translatedPage->description : null), ['class' => $errors->has($l.'.description') ? 'form-control is-invalid' : 'form-control', 'id' => $l.'-description', 'rows' => 3]) }}
                    @if($errors->has($l.'.description'))
                        <div class="invalid-feedback" style="display: block">
                            {{ $errors->first($l.'.description') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(app()->environment() !== 'production')
            <div class="form-group">
                <label>{{ __('page::page.labels.Layout') }}</label>
                {{ Form::select('layout', $layouts, old('layout', !empty($page)? $page->layout : 'master'), ['class' => 'form-control', 'id' => 'layout']) }}

            </div>
        @endif
        <div class="form-group">
            @mediaSingle('featured_image', $page ?? null, __('page::page.labels.Featured image'))
            @if($errors->has('medias_single.featured_image'))
                <div class="invalid-feedback">
                    {{ $errors->first('single_media.featured_image') }}
                </div>
            @endif
        </div>
        @if(!empty($page) && $page->define_fields)
            @foreach($page->decodeFields() as $cField)
                @php
                    $pageField = $page->customField($cField->name);
                @endphp
                <div class="form-group">
                    <label for="custom_fields-history">{{ $cField->label }}</label>
                    {{ Form::hidden('custom_fields['.$cField->name.'][locale]', $l) }}
                    @if(!empty($pageField))
                        {{ Form::hidden('custom_fields['.$cField->name.'][id]', $pageField->id) }}
                    @endif
                    {{ Form::hidden('custom_fields['.$cField->name.'][name]', $cField->name) }}
                    {{ Form::hidden('custom_fields['.$cField->name.'][label]', $cField->label) }}
                    {{ Form::hidden('custom_fields['.$cField->name.'][type]', $cField->type) }}
                    @if($cField->type === 'textarea' || $cField->type === 'textarea_rich')
                        {{ Form::textarea('custom_fields['.$cField->name.'][value]', old('custom_fields.'.$cField->name.".value", !empty($pageField) ? $pageField->value : null), ['class' => $errors->has('custom_fields.'.$cField->name) ? 'form-control is-invalid '.$cField->type : 'form-control '.$cField->type, 'id' => 'custom_fields-'.$cField->name, 'row' => 3]) }}
                    @elseif($cField->type === 'image')
                        <?php $media = $page->cfValue($cField->name); ?>
                        <div class="form-group media-directive">
                            <div class="clearfix"></div>

                            <button type="button" class="btn btn-primary btn-browse"
                                    onclick="openMediaWindowSingle(event, '{{ $cField->name }}');" <?php echo (isset($media->path)) ? 'style="display:none;"' : '' ?>>
                                <i class="la la-image"></i>
                                {{ trans('media::media.Browse') }}
                            </button>

                            <div class="clearfix"></div>

                            <ul id="thumbnails" class="jsThumbnailImageWrapper jsSingleThumbnailWrapper">
                                <?php if (isset($media->path)): ?>
                                <li data-id="{{ $media->id }}">
                                    <div class="preview">
                                        <button class="jsRemoveSimpleLink" href="#" title="{{ __('Remove') }}">
                                            <i class="icon ion-md-close"></i>
                                        </button>
                                        <div class="thumbnail">
                                            <div class="centered">
                                                <?php if ($media->media_type === 'image'): ?>
                                                <img
                                                    src="{{ Imagy::getThumbnail($media->path, (isset($thumbnail) ? $thumbnail : 'thumbnail')) }}"
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
                                <input type="hidden" name="custom_fields[{{ $cField->name }}][value]"
                                       value="{{ $media->id }}">
                                <?php else: ?>
                                <input type="hidden" name="custom_fields[{{ $cField->name }}][value]" value="">
                                <?php endif; ?>

                            </ul>
                            @if($errors->has('custom_fields.'.$cField->name.'.value'))
                                <div class="d-block">
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $errors->first('custom_fields.'.$cField->name.'.value') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        {{ Form::input($cField->type, 'custom_fields['.$cField->name.'][value]', old('custom_fields.'.$cField->name.".value", !empty($pageField) ? $pageField->value : null), ['class' => $errors->has('custom_fields.'.$cField->name) ? 'form-control is-invalid' : 'form-control', 'id' => 'custom_fields-'.$cField->name]) }}
                    @endif
                    <i class="form-group__bar"></i>
                    @if($errors->has('custom_fields.'.$cField->name.'.value'))
                        <div class="form-control-feedback" style="display: block">
                            {{ $errors->first('custom_fields.'.$cField->name.'.value') }}
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        <div class="form-group">
            <label>{{ __('page::page.labels.Status') }}</label>
            <?php
            $statuses = [
                1 => __('page::page.labels.Show'),
                0 => __('page::page.labels.Hide')
            ];
            ?>
            {{ Form::select('status', $statuses, old('status', !empty($page)? $page->status : 1), ['class' => 'form-control', 'id' => 'status']) }}

        </div>
        @if(app()->environment() !== 'production')
            <div class="form-group">
                <label for="">Markup</label>
                {{ Form::hidden('page_content', $pageContent ?? null, ['id' => 'page_content']) }}
                <div class="page-content">
                    <div id="ace">{{ $pageContent ?? null }}</div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Code</label>
                {{ Form::hidden('code_content', $codeContent ?? null, ['id' => 'code_content']) }}
                <div class="code-content">
                    <div id="ace-code">{{ $codeContent ?? null }}</div>
                </div>
            </div>
            <div class="form-group m-form__group">
                <label for="">Define Fields</label>
                <div class="box-editor">
                    <div id="editor-2">{{ !empty($page) ? $page->define_fields : '' }}</div>
                </div>
                {{ Form::hidden('define_fields', old('define_fields', !empty($page) ? $page->define_fields : ''), ['id' => 'define_fields']) }}
            </div>
        @endif
    </div>
</div>
@include('core::admin.seo', ['entity' => $page ?? null])
<div class="row">
    <div class="col-md-12">
        <div class="float-left">
            <a href="{{ route('admin.page.index') }}" class="btn btn-dark"><i
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
    {!! Theme::js('js/ace/ace.js') !!}
    <script>
        $(function () {
            // pass options to ace.edit
            var editor = ace.edit('ace', {
                mode: "ace/mode/html",
                selectionStyle: "text"
            });
            editor.getSession().on("change", function () {
                $('#page_content').val(editor.getSession().getValue());
            });
            var codeEditor = ace.edit('ace-code', {
                mode: "ace/mode/php",
                selectionStyle: "text"
            });
            codeEditor.getSession().on("change", function () {
                $('#code_content').val(codeEditor.getSession().getValue());
            });

            var editor2 = ace.edit("editor-2");
            // editor2.setTheme("ace/theme/twilight");
            editor2.session.setMode("ace/mode/json");

            editor2.getSession().on("change", function () {
                $('#define_fields').val(editor2.getSession().getValue());
            });

            @if(!empty($page) && $page->define_fields)
            @foreach($page->decodeFields() as $cField)
            @if($cField->type === 'textarea_rich')
            CKEDITOR.replace("custom_fields[{{ $cField->name }}][value]");
            @endif
            @endforeach
            @endif
        });
    </script>
    <script src="{{ Theme::url('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function () {
            $('.textarea_rich').each(function () {
                CKEDITOR.replace(this.id, {});
            });
        });
    </script>
@endpush
@push('css-stack')
    <style type="text/css" media="screen">
        .page-content, .code-content, .box-editor {
            position: relative;
            width: 100%;
            min-height: 500px;
        }

        #ace, #ace-code, #editor-2 {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
@endpush
