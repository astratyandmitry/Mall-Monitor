@php /** @var \stdClass[] $stats */ @endphp
@php /** @var array $visits */ @endphp
@php /** @var array $graphStats */ @endphp
@php /** @var array $graphVisits */ @endphp

@php $summary = [
    'count' => isset($graphStats['count']) ? array_sum($graphStats['count']) : 0,
    'amount' => isset($graphStats['amount']) ? array_sum($graphStats['amount']) : 0,
    'avg' => isset($graphStats['avg']) ? round(array_sum($graphStats['avg']) / count($graphStats['avg'])) : 0,
    'visits' => (isset($graphVisits['count'])) ? array_sum($graphVisits['count']) : 0,
] @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>

                <div class="heading-action">
                    @include('layouts.includes.field.dropdown', [
                        'attribute' => 'graph_date_type',
                        'options'  => $graph_date_types,
                        'without_placeholder' => true,
                    ])
                </div>
            </div>
        </div>
    </div>

    <div class="summary">
        <div class="container">
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number($summary['amount']) }} ₸
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            Сумма продаж
                        </div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number($summary['count']) }}
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            Количество продаж
                        </div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number($summary['avg']) }} ₸
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            Средний чек
                        </div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number($summary['visits']) }}
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            Посетителей
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            @if (count($stats))
                <div class="canvases">
                    <div class="box">
                        <div class="box-title">
                            <div class="box-title-text">
                                Сумма продаж:
                                <span>{{ number($summary['amount']) }} ₸</span>
                            </div>
                        </div>

                        <div class="box-content">
                            <div id="statistics-amount" class="rounded-sm mb-16"></div>
                        </div>
                    </div>

                    <div class="box is-marged">
                        <div class="box-title">
                            <div class="box-title-text">
                                Количество продаж:
                                <span>{{ number($summary['count']) }}</span>
                            </div>
                        </div>

                        <div class="box-content">
                            <div id="statistics-count"></div>
                        </div>
                    </div>

                    <div class="box is-marged">
                        <div class="box-title">
                            <div class="box-title-text">
                                Средний чек:
                                <span>{{ number($summary['avg']) }} ₸</span>
                            </div>
                        </div>

                        <div class="box-content">
                            <div id="statistics-avg"></div>
                        </div>
                    </div>

                    @if (count($visits))
                        <div class="box is-marged">
                            <div class="box-title">
                                <div class="box-title-text">
                                    Посещения:
                                    <span>{{ number($summary['visits']) }}</span>
                                </div>
                            </div>

                            <div class="box-content">
                                <div id="statistics-visits"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Статистика за 30 дней
                        </div>
                    </div>

                    <div class="box-content">
                        <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th nowrap>
                                    Дата
                                </th>
                                <th nowrap class="is-center" width="100">
                                    Конверсия
                                </th>
                                <th nowrap class="is-center" width="100">
                                    Посещений
                                </th>
                                <th nowrap class="is-center" width="100">
                                    Кол-во чек.
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
                            @php $statsTableTotal = new App\Classes\Design\StatsTableTotal @endphp
                            @foreach($stats as $stat)
                                @php $statsTableItem = new App\Classes\Design\StatsTableItem($stat, @$visits[$stat->date]) @endphp
                                @php $statsTableTotal->increase($statsTableItem) @endphp
                                <tr>
                                    <td nowrap>
                                        {{ $statsTableItem->getDateFormatted() }}
                                    </td>
                                    <td nowrap class="is-center">
                                        {{ $statsTableItem->getConversion() }}%
                                    </td>
                                    <td nowrap class="is-center">
                                        {{ number($statsTableItem->getVisitsCount()) }}
                                    </td>
                                    <td nowrap class="is-center">
                                        {{ number($statsTableItem->getChequesCount()) }}
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ number($statsTableItem->getChequesAvgAmount()) }} ₸
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ number($statsTableItem->getChequesAmount()) }} ₸
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2" style="text-align: right">Итого:</th>
                                <th nowrap class="is-center">
                                    {{ number($statsTableTotal->getCountVisits()) }}
                                </th>
                                <th nowrap class="is-center">
                                    {{ number($statsTableTotal->getChequesCount()) }}
                                </th>
                                <th nowrap class="is-right">
                                    {{ number($statsTableTotal->getChequesAvgAmount()) }} ₸
                                </th>
                                <th nowrap class="is-right">
                                    {{ number($statsTableTotal->getChequesAmount()) }} ₸
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! count($stats))
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

@if (count($stats))
    @push('scripts')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script>
            function chartOptions(series, categories, data) {
                return {
                    exporting: {
                        chartOptions: {
                            title: {
                                text: data.title,
                                margin: 10,
                                style: {
                                    color: 'black',
                                }
                            },

                            subtitle: {
                                text: 'Тестовое описание',
                            },

                            plotOptions: {
                                series: {
                                    dataLabels: {
                                        enabled: false
                                    }
                                }
                            }
                        },
                        filename: 'keruenmonitor-chart',
                        printMaxWidth: 780 * 3,
                        scale: 2,
                        fallbackToExportServer: true,
                    },

                    tooltip: {
                        backgroundColor: '#fff',
                        borderColor: '#38c172',
                        borderRadius: 4,
                        borderWidth: 1.5,
                    },

                    title: false,
                    subtitle: false,
                    legend: false,
                    series: series,

                    xAxis: {
                        gridLineWidth: 1,
                        categories: categories
                    },

                    yAxis: {
                        gridLineWidth: 1,
                        title: false,
                    },

                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                        }
                    },
                }
            }

            Highcharts.chart('statistics-amount', chartOptions([ {
                color: '#38c172',
                name: 'Сумма продаж',
                data: @json($series['amount'])
            } ], @json(array_map(function($item) { return $item['name']; }, $series['amount'])), {
                title: 'Сумма продаж',
            }));

            Highcharts.chart('statistics-avg', chartOptions([ {
                color: '#38c172',
                name: 'Средний чек',
                data: @json($series['avg'])
            } ], @json(array_map(function($item) { return $item['name']; }, $series['amount'])), {
                title: 'Средний чек',
            }));

            Highcharts.chart('statistics-count', chartOptions([ {
                color: '#38c172',
                name: 'Количество продаж',
                data: @json($series['count'])
            } ], @json(array_map(function($item) { return $item['name']; }, $series['amount'])), {
                title: 'Количество продаж',
            }));

            @if (isset($series['visits']) && count($series['visits']))
            Highcharts.chart('statistics-visits', chartOptions([ {
                color: '#38c172',
                name: 'Посетителей',
                data: @json($series['count'])
            } ], @json(array_map(function($item) { return $item['name']; }, $series['visits'])), {
                title: 'Количество посетителей',
            }));
            @endif
        </script>
    @endpush
@endif
