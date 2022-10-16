<div class="field is-checkbox @if ($errors->has("{$data_key}.{$attribute}")) is-invalidate @endif">
  <input type="hidden" name="{{ $attribute }}" value="0">
  <input
    name="{{ $data_key }}[{{ $attribute }}]" id="{{ $data_key }}.{{ $attribute }}" type="checkbox" value="1"
    @if(old("{$data_key}.{$attribute}", @$entity->{$data_key}[$attribute], 0) == 1) checked @endif
    @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
    @if (isset($disabled) && $disabled) disabled @endif
    @if (isset($required) && $required) required @endif
    @if (isset($autofocus) && $autofocus) autofocus @endif
  >

  <label for="{{ $data_key }}.{{ $attribute }}">
    <span>
      {{ $label }}

      @if (isset($required) && $required == true)
        <strong>*</strong>
      @endif
    </span>
  </label>

  @include('layouts.includes.array.error')
</div>
