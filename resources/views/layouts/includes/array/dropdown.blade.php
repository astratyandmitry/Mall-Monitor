<div class="field @if ($errors->has("{$data_key}.{$attribute}")) is-invalidate @endif">
  @if (isset($label))
    <label for="{{ $data_key }}.{{ $attribute }}">
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

  <select
    name="{{ $data_key }}[{{ $attribute }}]" id="{{ $data_key }}.{{ $attribute }}"
    @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
    @if (isset($disabled) && $disabled) disabled @endif
    @if (isset($required) && $required) required @endif
    @if (isset($autofocus) && $autofocus) autofocus @endif
  >
    <option selected value="">{{ (isset($placeholder)) ? $placeholder : null }}</option>
    @foreach($options as $key => $option)
      @include('layouts.includes.array.dropdown-' . ((is_object($option)) ?  'object' : 'array'))
    @endforeach
  </select>

  @include('layouts.includes.array.error')
</div>
