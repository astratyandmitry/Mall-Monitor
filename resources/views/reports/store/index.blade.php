@php /** @var array $statistics */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var array $store_names */ @endphp

@php $exportParams = request()->only(['mall_id', 'store_id', 'date_from', 'time_from', 'date_to', 'time_to', 'type_id', 'sort', 'limit', 'sort_key', 'sort_value']) @endphp

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
                        <span>{{ isRequestEmpty() ? 'Показать' : 'Скрыть' }} фильтр</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('reports.store.partials.filter')

    @if (count($statistics))
        <div class="content">
            <div class="container">
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Статистика арендаторов
                        </div>

                        <div class="box-title-action">
                            <a href="{{ route('reports.store.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </a>

                            <a href="{{ route('reports.store.export.excel', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-excel-o"></i>
                                Скачать Excel
                            </a>
                        </div>
                    </div>

                    <div class="box-content">
                        <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th width="240">
                                    ТРЦ
                                    @include('layouts.includes.table.sorting', ['attribute' => 'mall_id', 'default_key' => 'store_id'])
                                </th>
                                <th nowrap>
                                    Арендатор
                                    @include('layouts.includes.table.sorting', ['attribute' => 'store_id', 'default_key' => 'store_id'])
                                </th>
                                <th nowrap class="is-center" width="100">
                                    Кол-во чек.
                                    @include('layouts.includes.table.sorting', ['attribute' => 'count', 'default_key' => 'store_id'])
                                </th>
                                <th nowrap class="is-right" width="120">
                                    Сред. чек.
                                    @include('layouts.includes.table.sorting', ['attribute' => 'avg', 'default_key' => 'store_id'])
                                </th>
                                <th nowrap class="is-right" width="160">
                                    Сумма продаж
                                    @include('layouts.includes.table.sorting', ['attribute' => 'amount', 'default_key' => 'store_id'])
                                </th>
                                @if ($isGroupByDates)
                                    <th nowrap class="is-right" width="140">
                                        Дата
                                        @include('layouts.includes.table.sorting', ['attribute' => 'created_at', 'default_key' => 'created_at', 'default_type' => 'desc'])
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @php $amount = 0 @endphp
                            @php $count = 0 @endphp
                            @foreach($statistics as $statistic)
                                @php $amount += $statistic['amount'] @endphp
                                @php $count += $statistic['count'] @endphp
                                <tr>
                                    <td nowrap>
                                        {{ $mall_names[$statistic['mall_id']] }}
                                    </td>
                                    <td nowrap>
                                        {{ $stores[$statistic['store_id']]['name'] }}
                                    </td>
                                    <td nowrap class="is-center">
                                        {{ number_format($statistic['count']) }}
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ number_format(round($statistic['avg'])) }} ₸
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ number_format($statistic['amount']) }} ₸
                                    </td>
                                    @if ($isGroupByDates)
                                        <td nowrap class="is-right" width="140 ">
                                            {{ date('d.m.Y', strtotime($statistic['date'])) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th style="text-align: right">Итого:</th>
                                <th nowrap class="is-center">
                                    {{ number_format($count) }}
                                </th>
                                <th nowrap class="is-right">
                                    {{ number_format(round($amount / $count)) }} ₸
                                </th>
                                <th nowrap class="is-right">
                                    {{ number_format($amount) }} ₸
                                </th>
                                @if ($isGroupByDates)
                                    <th></th>
                                @endif
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
