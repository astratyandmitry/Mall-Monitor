<div class="field @if ($errors->has($attribute)) is-invalidate @endif">
  @if (isset($label))
    <label for="{{ $attribute }}">
      <span>
        {{ $label }}

        @if (isset($required) && $required == true)
          <strong>*</strong>
        @endif
      </span>

      @if (isset($helper))
        <span class="is-details">
          {!! $helper !!}
        </span>
      @endif
    </label>
  @endif

  <input
    type="password" name="{{ $attribute }}" id="{{ $attribute }}"
    @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
    @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
    @if (isset($classes)) class="{{ (is_array($classes)) ? implode(' ', $classes) : $classes }}" @endif
    @if (isset($disabled) && $disabled) disabled @endif
    @if (isset($required) && $required) required @endif
    @if (isset($autofocus) && $autofocus) autofocus @endif
  />

  @include('layouts.includes.form.error')
</div>
