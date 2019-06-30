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

    <input
            type="{{ isset($type) ? $type : 'text' }}" name="{{ $data_key }}[{{ $attribute }}]" id="{{ $data_key }}.{{ $attribute }}"
            @if (!isset($forceValue)) value="{{ old("{$data_key}.{$attribute}", @$entity->{$data_key}[$attribute]) }}" @else value="{{ $forceValue }}" @endif
            @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
            @if (isset($tabindex)) tabindex="{{ $tabindex }}" @endif
            @if (isset($disabled) && $disabled) disabled @endif
            @if (isset($required) && $required) required @endif
            @if (isset($autofocus) && $autofocus) autofocus @endif
    />

    @include('layouts.includes.array.error')
</div>
