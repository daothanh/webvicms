<?php
$seo = null;

?>
<div class="card collapsed-card" id="seo-card">
    <div class="card-header">
        <h3 class="card-title">
            {{ __('core::seo.title.Search Engine Optimize') }}
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus" id="collapse-btn"></i>
            </button>
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
                    @if($l === $language->code)
                        <li class="nav-item">
                            <a class="nav-link @if($language->code === $l) active @endif"
                               id="{{ $language->code }}-stab"
                               data-toggle="tab" href="#stab-{{ $language->code }}" role="tab"
                               aria-controls="{{ $language->code }}" aria-selected="true">{{ $language->native }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <div class="tab-content" id="seo-tab">
                @endif
                @foreach($languages as $language)
                    @if($l === $language->code)
                        @php
                            $lang = $language->code;
                            $translatedPage = null;

                            if (!empty($entity)) {
                                $seo = $entity->seoByLocale($lang);
                            }
                        @endphp
                        @if(!empty($seo))
                            {{ Form::hidden("seo[{$lang}][id]", $seo->id) }}
                        @endif
                        @if($hasMoreLanguages)
                            <div class="pt-3 tab-pane fade show @if($lang === $l) active @endif"
                                 id="stab-{{ $lang }}" role="tabpanel" aria-labelledby="{{ $lang }}-stab">
                                @endif
                                <div class="form-group @if($errors->has("seo.{$lang}.title")) has-danger @endif">
                                    <label>{{ __('core::seo.labels.Title', [], $lang) }}</label>
                                    {{ Form::text("seo[{$lang}][title]", old("seo.{$lang}.title", !empty($seo) ? $seo->title : null), ['class' => $errors->has("seo.{$lang}.title") ? 'form-control is-invalid' : 'form-control', 'id' => "seo-{$lang}-title"]) }}
                                    @if($errors->has("seo.{$lang}.title"))
                                        <div class="form-control-feedback">
                                            {{ $errors->first("seo.{$lang}.title") }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group @if($errors->has("seo.{$lang}.description")) has-danger @endif">
                                    <label>{{ __('core::seo.labels.Description', [], $lang) }}</label>
                                    {{ Form::textarea("seo[{$lang}][description]", old("seo.{$lang}.description", !empty($seo) ? $seo->description : null), ['class' => $errors->has("seo.{$lang}.description") ? 'form-control is-invalid' : 'form-control', 'id' => "seo-{$lang}-description"]) }}
                                    @if($errors->has("seo.{$lang}.description"))
                                        <div class="form-control-feedback">
                                            {{ $errors->first("seo.{$lang}.description") }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group @if($errors->has("seo.{$lang}.keywords")) has-danger @endif">
                                    <label>{{ __('core::seo.labels.Keywords', [], $lang) }}</label>
                                    {{ Form::text("seo[{$lang}][keywords]", old("seo.{$lang}.keywords", !empty($seo) ? $seo->keywords : null), ['class' => $errors->has("seo.{$lang}.keywords") ? 'form-control is-invalid' : 'form-control', 'id' => "seo-{$lang}-keywords"]) }}
                                    @if($errors->has("seo.{$lang}.keywords"))
                                        <div class="form-control-feedback">
                                            {{ $errors->first("seo.{$lang}.keywords") }}
                                        </div>
                                    @endif
                                </div>
                                @if($hasMoreLanguages)
                            </div>
                        @endif
                    @endif
                @endforeach
                @if($hasMoreLanguages)
            </div>
        @endif
    </div>
</div>
@push('js-stack')
    <script>
        $(function () {
            let seoCollapse = localStorage.getItem('seoCollapse') === null ? 0 : 1;
            if (seoCollapse === 1) {
                $('#seo-card').addClass('collapsed-card');
                $('#collapse-btn').removeClass('minus').addClass('fa-plus');
            } else {
                $('#seo-card').removeClass('collapsed-card');
                $('#collapse-btn').removeClass('fa-plus').addClass('fa-minus');
            }

            $('#collapse-btn').click(function () {
                if (seoCollapse === 0) {
                    localStorage.setItem('seoCollapse', '1');
                    seoCollapse = 1;
                } else {
                    localStorage.removeItem('seoCollapse');
                    seoCollapse = 0;
                }
            })
        })
    </script>
@endpush
