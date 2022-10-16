<option value="">{{ $placeholder ?? '' }}</option>
@if (count($entities))
  @foreach($entities as $id => $value)
    <option value="{{ $id }}" @if ($selected == $id) selected @endif>{{ $value }}</option>
  @endforeach
@endif
