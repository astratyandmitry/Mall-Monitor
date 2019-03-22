<option value="{{ $option->id }}" @if(isset($_GET[$attribute]) && $_GET[$attribute] == $option->id) selected @endif>
    {{ (isset($display)) ? $option->{$display} : $option->title }}
</option>
