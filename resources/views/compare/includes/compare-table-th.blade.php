@php
    /** @var array $statistics_current */
    /** @var array $statistics_past */
    /** @var integer $mall_id */
    /** @var string $key */

    $_diff = compare_diff($_current, $_past);
    $_currency = (isset($currency) && $currency == true) ? true : false;
@endphp
<th nowrap class="is-right {{ compare_background($_diff) }}">
    {{ number_format($_current) }} {{ $_currency ? '₸' : '' }}<br/>
    {{ number_format($_past) }} {{ $_currency ? '₸' : '' }}<br/>
    <strong class="{{ compare_color($_diff) }}">{{ $_diff }}% <i class="fa fa-arrow-{{ compare_arrow($_diff) }}"></i></strong>
</th>