<div class="tab-content" id="pills-tabContent">
    @foreach ($locales as $l)
        <div class="tab-pane fade show @if($l === $currentLocale) active @endif" id="{{$l}}"
                             role="tabpanel" aria-labelledby="{{$l}}-tab">
            <div class="form-group @if($errors->has('s.website.name.'.$l.'')) has-danger @endif">
                <label>{{ __('core::settings.labels.website.Name') }}</label>
                {{ Form::text('s[website][name]['.$l.']', old('s.website.name.'.$l.'', !empty($s['website']) && !empty($s['website']['name']) && !empty($s['website']['name'][$l]) ? $s['website']['name'][$l] : null), ['class' => 'form-control m-input']) }}
                @if($errors->has('s.website.name.'.$l.''))
                    <div class="form-control-feedback">
                        {{ $errors->first('s.website.name.'.$l.'') }}
                    </div>
                @endif
            </div>
            <div class="form-group @if($errors->has('s.website.slogan.'.$l.'')) has-danger @endif">
                <label>{{ __('core::settings.labels.website.Slogan') }}</label>
                {{ Form::text('s[website][slogan]['.$l.']', old('s.website.slogan.'.$l.'', !empty($s['website']) && !empty($s['website']['slogan']) && !empty($s['website']['slogan'][$l]) ? $s['website']['slogan'][$l] : null), ['class' => 'form-control m-input']) }}
                @if($errors->has('s.website.slogan.'.$l.''))
                    <div class="form-control-feedback">
                        {{ $errors->first('s.website.slogan.'.$l.'') }}
                    </div>
                @endif
            </div>

            <div class="form-group @if($errors->has('s.website.description.'.$l.'')) has-danger @endif">
                <label>{{ __('core::settings.labels.website.Description') }}</label>
                {{ Form::textarea('s[website][description]['.$l.']', old('s.website.description.'.$l.'',!empty($s['website']) && !empty($s['website']['description']) && !empty($s['website']['description'][$l]) ? $s['website']['description'][$l] : null), ['class' => 'form-control m-input', 'id' => 's-website-description-'.$l, 'rows' => 5]) }}
                @if($errors->has('s.website.description.'.$l.''))
                    <div class="form-control-feedback">
                        {{ $errors->first('s.website.description.'.$l.'') }}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

@push('js-stack')
    <script>
        $(function () {
            $('select#locales').select2({
                tags: true,
            });
        });

    </script>
@endpush
