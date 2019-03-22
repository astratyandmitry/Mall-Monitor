<input
        type="hidden" name="{{ $attribute }}" id="{{ $attribute }}"
        value="{{ old($attribute, (isset($entity)) ? $entity->{$attribute} : @$value) }}"
/>
