<option value="{{ $key }}" @if(isset($_GET[$attribute]) && $_GET[$attribute] == $key) selected @endif>
    {{ $option }}
</option>
