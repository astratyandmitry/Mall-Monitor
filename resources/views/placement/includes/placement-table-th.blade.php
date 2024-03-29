@php
  /** @var array $statsCurrent */
  /** @var array $statsPast */
  /** @var integer $mall_id */
  /** @var string $key */

  $_diff = placement_diff($_current, $_past);
  $_currency = (isset($currency) && $currency == true) ? true : false;
@endphp
<th nowrap
    class="is-right {{ ($_current != $_past && ! ($_current == 0 && $_past == 0)) ? placement_background($_diff) : '' }}">
  <span class="period">тек.:</span> {{ number($_current) }} {{ $_currency ? '₸' : '' }}<br/>
  <span class="period">пред.:</span> {{ number($_past) }} {{ $_currency ? '₸' : '' }}<br/>

  @if ($_current != $_past && ! ($_current == 0 && $_past == 0))
    <strong class="{{ placement_color($_diff) }}">
      {{ $_diff }}% <i class="fa fa-arrow-{{ placement_arrow($_diff) }}"></i>
    </strong>
  @endif
</th>
