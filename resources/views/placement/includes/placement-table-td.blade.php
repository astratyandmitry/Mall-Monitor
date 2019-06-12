@php
    /** @var array $statistics_current */
   /** @var array $statistics_past */
   /** @var integer $mall_id */
   /** @var string $key */

   $_current = placement_value($statistics_current, isset($store_id) ? $store_id : $mall_id, $key);
   $_past = placement_value($statistics_past, isset($store_id) ? $store_id : $mall_id, $key);
   $_diff = placement_diff($_current, $_past);
   $_currency = (isset($currency) && $currency == true) ? true : false;
@endphp
<td nowrap class="is-right {{ placement_background($_diff) }}">
    {{ number_format($_current) }} {{ $_currency ? '₸' : '' }}<br/>
    {{ number_format($_past) }} {{ $_currency ? '₸' : '' }}<br/>
    <strong class="{{ placement_color($_diff) }}">{{ $_diff }}% <i class="fa fa-arrow-{{ placement_arrow($_diff) }}"></i></strong>
</td>