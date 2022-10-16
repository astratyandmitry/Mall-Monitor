@php $sort_key = isset($_GET['sort_key']) ? $_GET['sort_key'] : $default_key ?? 'id' @endphp
@php $sort_type = isset($_GET['sort_type']) ? $_GET['sort_type'] : $default_type ?? 'asc' @endphp
@php $sort_type_reverse = $sort_type == 'asc' ? 'desc' : 'asc' @endphp

@if ($sort_key != $attribute)
  <a
    href="{{ route("{$globals['active_page']}.index", paginateAppends(['sort_key' => $attribute, 'sort_type' => $default_type ?? 'asc'])) }}">
    <i class="fa fa-sort"></i>
  </a>
@else
  <a
    href="{{ route("{$globals['active_page']}.index", paginateAppends(['sort_key' => $attribute, 'sort_type' => $sort_type_reverse])) }}">
    <i class="fa fa-sort-{{ $sort_type }}"></i>
  </a>
@endif
