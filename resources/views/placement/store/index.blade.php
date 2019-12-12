@php /** @var array $statsCurrent */ @endphp
@php /** @var array $statsPast */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var array $store_names */ @endphp

@php $exportParams = paginateAppends() @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>

                <div class="heading-filter">
                    <div class="heading-filter-button">
                        <i class="fa fa-filter"></i>
                        <span>Скрыть фильтр</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('placement.store.partials.filter')

    @if (count($statsCurrent) || count($statsPast))
        <div class="content">
            <div class="container">
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Сравнение <span class="badge">{{ $dates['current'] }}</span> с <span class="badge">{{ $dates['past'] }}</span>
                        </div>
                    </div>

                    <div class="box-content">
                        <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th nowrap width="240">
                                    ТРЦ
                                </th>
                                <th nowrap>
                                    Арендатор
                                </th>
                                <th nowrap class="is-right" width="100">
                                    Посещений
                                </th>
                                <th nowrap class="is-right" width="100">
                                    Кол-во чек.
                                    @include('layouts.includes.table.sorting', ['attribute' => 'count', 'default_key' => 'mall_id'])
                                </th>
                                <th nowrap class="is-right" width="120">
                                    Сред. чек.
                                </th>
                                <th nowrap class="is-right" width="160">
                                    Сумма продаж
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $visits_current = 0 @endphp
                            @php $amount_current = 0 @endphp
                            @php $count_current = 0 @endphp
                            @php $visits_past = 0 @endphp
                            @php $amount_past = 0 @endphp
                            @php $count_past = 0 @endphp
                            @foreach($store_names as $store_id => $store)
                                @php $visits_current += placement_value($statsCurrent, $store_id, 'visits') @endphp
                                @php $amount_current += placement_value($statsCurrent, $store_id, 'amount') @endphp
                                @php $count_current += placement_value($statsCurrent, $store_id, 'count') @endphp
                                @php $visits_past += placement_value($statsPast, $store_id, 'visits') @endphp
                                @php $amount_past += placement_value($statsPast, $store_id, 'amount') @endphp
                                @php $count_past += placement_value($statsPast, $store_id, 'count') @endphp
                                <tr style="line-height: 1.4">
                                    <td nowrap>
                                        {{ $mall_names[$store['mall_id']] }}
                                    </td>
                                    <td nowrap>
                                        {{ $store['name'] }}
                                    </td>
                                    @include('placement.includes.placement-table-td', ['key' => 'visits'])
                                    @include('placement.includes.placement-table-td', ['key' => 'count'])
                                    @include('placement.includes.placement-table-td', ['key' => 'avg'])
                                    @include('placement.includes.placement-table-td', ['key' => 'amount', 'currency' => true])
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2" style="text-align: right">Итого:</th>
                                @include('placement.includes.placement-table-th', [
                                  '_current' => $visits_current,
                                  '_past' => $visits_past,
                              ])
                                @include('placement.includes.placement-table-th', [
                                    '_current' => $count_current,
                                    '_past' => $count_past,
                                ])
                                @include('placement.includes.placement-table-th', [
                                    '_current' => ($amount_current == 0 || $count_current == 0) ? 0 : round($amount_current / $count_current),
                                    '_past' => ($amount_past == 0 || $count_past == 0) ? 0 : round($amount_past / $count_past),
                                ])
                                @include('placement.includes.placement-table-th', [
                                    '_current' => $amount_current,
                                    '_past' => $amount_past,
                                ])
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="information">
            <div class="container">
                <div class="information-box is-lg">
                    <div class="information-box-text">
                        Информация по указанному запросу отсутствует
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
