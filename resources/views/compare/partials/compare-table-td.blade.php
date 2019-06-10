@php
    /** @var array $statistics_current */
   /** @var array $statistics_past */
   /** @var integer $mall_id */
   /** @var string $key */

   $_current = compare_value($statistics_current, $mall_id, $key);
   $_past = compare_value($statistics_past, $mall_id, $key);
   $_diff = compare_diff($_current, $_past);
@endphp
<td nowrap class="is-right {{ compare_background($_diff) }}">
    {{ number_format($_current) }}<br/>
    {{ number_format($_past) }}<br/>
    <strong class="{{ compare_color($_diff) }}">{{ $_diff }}% <i class="fa fa-arrow-{{ compare_arrow($_diff) }}"></i></strong>
</td>