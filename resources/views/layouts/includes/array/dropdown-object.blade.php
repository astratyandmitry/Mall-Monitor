<option value="{{ $option->id }}" @if(old("{$data_key}.{$attribute}", @$entity->{$data_key}[$attribute]) == $option->id) selected @endif>
    {{ (isset($display)) ? $option->{$display} : $option->title }}
</option>
