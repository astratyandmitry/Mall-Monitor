<input
  type="{{ isset($type) ? $type : 'text' }}" name="{{ $attribute }}" id="{{ $attribute }}"
  value="{{ isset($_GET[$attribute]) ? $_GET[$attribute] : null }}"
  @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
  @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
  @if (isset($classes)) class="{{ (is_array($classes)) ? implode(' ', $classes) : $classes }}" @endif
  @if (isset($disabled) && $disabled) disabled @endif
  @if (isset($required) && $required) required @endif
  @if (isset($autofocus) && $autofocus) autofocus @endif
/>
