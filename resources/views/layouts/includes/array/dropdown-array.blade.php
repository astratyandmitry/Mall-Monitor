<option value="{{ $key }}" @if(old("{$data_key}.{$attribute}", @$entity->{$data_key}[$attribute]) == $key) selected @endif>
    {{ $option }}
</option>
