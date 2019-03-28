@php /** @var array $statistics */ @endphp
@php /** @var \App\Models\Mall $mall */ @endphp

@php $exportParams = request()->only(['date_from', 'date_to']) @endphp

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

    <div class="filter {{ isRequestEmpty() ? 'is-hidden' : '' }}">
        <div class="container">
            <form method="GET" class="filter-form">
                @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'id'])
                @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'asc'])

                <div class="grid">
                    @include('layouts.includes.form.input', [
                        'attribute' => 'date_from',
                        'value' => request()->query('date_from'),
                        'label' => 'Дата начала',
                        'type' => 'datetime-local',
                         'placeholder' => 'yyyy-mm-dd HH:ii',
                    ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'date_to',
                        'value' => request()->query('date_to'),
                        'label' => 'Дата окончания',
                        'type' => 'datetime-local',
                         'placeholder' => 'yyyy-mm-dd HH:ii',
                    ])
                </div>

                <button type="submit" class="btn">Применить фильтр</button>
            </form>
        </div>
    </div>

    @if (count($statistics))
        <div class="content">
            <div class="container">
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Статистика ТРЦ
                        </div>

                        <div class="box-title-action">
                            <a href="{{ route('reports.mall.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </a>

                            <a href="{{ route('reports.mall.export.excel', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-excel-o"></i>
                                Скачать Excel
                            </a>
                        </div>
                    </div>

                    <div class="box-content">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th nowrap>
                                    Арендатор
                                    @include('layouts.includes.table.sorting', ['attribute' => 'mall_id', 'default_key' => 'mall_id'])
                                </th>
                                <th nowrap class="is-center" width="100">
                                    Кол-во
                                    @include('layouts.includes.table.sorting', ['attribute' => 'count', 'default_key' => 'mall_id'])
                                </th>
                                <th nowrap class="is-right" width="120">
                                    Сред. чек
                                    @include('layouts.includes.table.sorting', ['attribute' => 'avg', 'default_key' => 'mall_id'])
                                </th>
                                <th nowrap class="is-right" width="160">
                                    Сумма
                                    @include('layouts.includes.table.sorting', ['attribute' => 'amount', 'default_key' => 'mall_id'])
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $amount = 0 @endphp
                            @php $count = 0 @endphp
                            @foreach($statistics as $statistic)
                                @php $amount += $statistic['amount'] @endphp
                                @php $count += $statistic['count'] @endphp
                                @php $mall = \App\Models\Mall::find($statistic['mall_id']) @endphp
                                <tr>
                                    <td nowrap>
                                        {{ $mall->name }}
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
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th nowrap class="is-center">
                                    {{ number_format($count) }}
                                </th>
                                <th nowrap class="is-right">
                                    {{ number_format(round($amount / $count)) }} ₸
                                </th>
                                <th nowrap class="is-right">
                                    {{ number_format($amount) }} ₸
                                </th>
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

@push('scripts')
    <script>
        $(function () {
            var $filter = $('.filter');
            var $filterToggle = $('.heading-filter-button');

            $filterToggle.on('click', function () {
                if ($filter.is(':visible')) {
                    $filterToggle.find('span').text('Показать фильтр');
                } else {
                    $filterToggle.find('span').text('Скрыть фильтр');
                }

                $filter.slideToggle(160);
            });
        });
    </script>
@endpush

