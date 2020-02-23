<?php
$nameArr = explode(".", $name);
$realName = "s[".implode("][", $nameArr)."]";
if(isset($multiple) && $multiple === true) {
    $realName .= "[]";
}
?>
<div class="form-group @if($errors->has("s.$name")) has-danger @endif">
    <label>{{ $label }}</label>
    {{ Form::select($realName, $options ?? [] ,old("s.$name", Illuminate\Support\Arr::get($s, $name) ?? $default), ['class' => 'form-control m-input', 'multiple' => isset($multiple) && $multiple === true, 'id' => implode("-", $nameArr)]) }}
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

