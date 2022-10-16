<option value="{{ $key }}"
        @if(old($attribute, (isset($entity)) ? $entity[$attribute] : @$value) == $key) selected @endif>
  {{ $option }}
</option>
