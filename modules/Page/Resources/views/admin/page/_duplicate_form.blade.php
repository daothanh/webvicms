{{ Form::open(['route' => 'admin.page.store', 'method' => 'post', 'files' => true, 'id' => 'frm-page']) }}

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
                $l = $language->code;
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
        <div class="form-group">
            <label>{{ __('page::page.labels.Layout') }}</label>
            {{ Form::select('layout', $layouts, old('layout', !empty($page)? $page->layout : 'master'), ['class' => 'form-control', 'id' => 'layout']) }}

        </div>
        <div class="form-group">
            @mediaSingle('featured_image', $page ?? null, __('page::page.labels.Featured image'))
            @if($errors->has('medias_single.featured_image'))
                <div class="invalid-feedback">
                    {{ $errors->first('single_media') }}
                </div>
            @endif
        </div>
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
        <div class="form-group">
            <label for="">Markup</label>
            {{ Form::hidden('page_content', $pageContent ?? null, ['id' => 'page_content']) }}
            <div class="page-content">
                <div id="ace">{{ $pageContent ?? null }}</div>
            </div>
        </div>
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
                    class="icon ion-md-save"></i> {{ __('Duplicate') }}</button>
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
        });
    </script>
@endpush
@push('css-stack')
    <style type="text/css" media="screen">
        .page-content {
            position: relative;
            width: 100%;
            min-height: 500px;
        }

        #ace {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
@endpush
