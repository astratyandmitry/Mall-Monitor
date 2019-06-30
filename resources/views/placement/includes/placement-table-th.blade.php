@php
    /** @var array $statistics_current */
    /** @var array $statistics_past */
    /** @var integer $mall_id */
    /** @var string $key */

    $_diff = placement_diff($_current, $_past);
    $_currency = (isset($currency) && $currency == true) ? true : false;
@endphp
<th nowrap class="is-right {{ placement_background($_diff) }}">
    <span class="period">тек.:</span> {{ number_format($_current) }} {{ $_currency ? '₸' : '' }}<br/>
    <span class="period">пред.:</span> {{ number_format($_past) }} {{ $_currency ? '₸' : '' }}<br/>
    <strong class="{{ placement_color($_diff) }}">{{ $_diff }}% <i class="fa fa-arrow-{{ placement_arrow($_diff) }}"></i></strong>
</th>