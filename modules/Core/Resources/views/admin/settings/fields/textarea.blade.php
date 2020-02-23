<?php
    $nameArr = explode(".", $name);
    $realName = "s[".implode("][", $nameArr)."]";
?>
<div class="form-group @if($errors->has("s.$name")) has-danger @endif">
    <label>{{ $label }}</label>
    {{ Form::textarea($realName, old("s.$name", Illuminate\Support\Arr::get($s, $name) ?? $default), ['class' => 'form-control m-input']) }}
    @if($errors->has("s.$name"))
        <div class="form-control-feedback">
            {{ $errors->first("s.$name") }}
        </div>
    @endif
    @if(!empty($description))
        <span class="m-form__help">
        {{ $description }}
    </span>
    @endif
</div>

