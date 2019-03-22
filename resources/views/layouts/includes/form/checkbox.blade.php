<div class="field is-checkbox @if ($errors->has($attribute)) is-invalidate @endif">
    <input type="hidden" name="{{ $attribute }}" value="0">
    <input
            id="{{ $attribute }}" name="{{ $attribute }}" type="checkbox" value="1"
            @if(old($attribute, @$entity->{$attribute}) == 1) checked @endif
            @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
            @if (isset($disabled) && $disabled) disabled @endif
            @if (isset($required) && $required) required @endif
            @if (isset($autofocus) && $autofocus) autofocus @endif
    >

    <label for="{{ $attribute }}">
        <span>
            {{ $label }}
        </span>
    </label>

    @include('layouts.includes.form.error')
</div>
