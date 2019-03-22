<option value="{{ $option->id }}" @if(old($attribute, (isset($entity)) ? $entity->{$attribute} : @$value) == $option->id) selected @endif>
    {{ (isset($display)) ? $option->{$display} : $option->title }}
</option>
