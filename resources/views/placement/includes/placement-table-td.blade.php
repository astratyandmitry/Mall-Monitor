@php
    /** @var array $statsCurrent */
   /** @var array $statsPast */
   /** @var integer $mall_id */
   /** @var string $key */

   $_current = placement_value($statsCurrent, isset($store_id) ? $store_id : $mall_id, $key);
   $_past = placement_value($statsPast, isset($store_id) ? $store_id : $mall_id, $key);
   $_diff = placement_diff($_current, $_past);
   $_currency = (isset($currency) && $currency == true) ? true : false;
@endphp
<td nowrap class="is-right {{ placement_background($_diff) }}">
    <span class="period">тек.:</span> {{ number_format($_current) }} {{ $_currency ? '₸' : '' }}<br/>
    <span class="period">пред.:</span> {{ number_format($_past) }} {{ $_currency ? '₸' : '' }}<br/>
    @if ($_current != $_past)
        <strong class="{{ placement_color($_diff) }}">{{ $_diff }}% <i class="fa fa-arrow-{{ placement_arrow($_diff) }}"></i></strong>
    @endif
</td>
