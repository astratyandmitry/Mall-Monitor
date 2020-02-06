@php /** @var array $graphStats */ @endphp
@php /** @var array $graphVisits */ @endphp
@php /** @var boolean $statsExists */ @endphp
@php /** @var boolean $visitsExists */ @endphp
@php /** @var array $graphDateTypes*/ @endphp

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

    @include('compare.store.partials.filter')

    <div class="content">
        <div class="container">
            @if ($statsExists)
                <div class="box">
                    <div class="box-title">
                        <div class="box-title-text">
                            Сумма продаж
                        </div>
                    </div>

                    <div class="box-content">
                        <div id="statistics-amount"></div>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Количество продаж
                        </div>
                    </div>

                    <div class="box-content">
                        <div id="statistics-count"></div>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Средний чек
                        </div>
                    </div>

                    <div class="box-content">
                        <div id="statistics-avg"></div>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Посещения
                        </div>
                    </div>

                    <div class="box-content">
                        <div id="statistics-visits"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if (! $statsExists)
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

@if ($statsExists)
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
                        shared: true,
                        crosshairs: true,
                    },

                    title: false,
                    subtitle: false,
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top'
                    },
                    series: series,

                    xAxis: {
                        gridLineWidth: 1,
                        categories: categories
                    },

                    yAxis: {
                        gridLineWidth: 1,
                        title: true,
                    },

                    chart: {
                        zoomType: 'xy',
                    },

                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: false
                            },
                        }
                    },
                }
            }

            Highcharts.chart('statistics-amount', chartOptions(@json($series['avg']), @json(array_values($graph['labels'])), {
                title: 'Сумма продаж',
            }));

            Highcharts.chart('statistics-avg', chartOptions(@json($series['avg']), @json(array_values($graph['labels'])), {
                title: 'Средний чек',
            }));

            Highcharts.chart('statistics-count', chartOptions(@json($series['count']), @json(array_values($graph['labels'])), {
                title: 'Количество продаж',
            }));

            Highcharts.chart('statistics-visits', chartOptions(@json($series['visits']), @json(array_values($graph['labels'])), {
                title: 'Количество посетителей',
            }));
        </script>
    @endpush
@endif
