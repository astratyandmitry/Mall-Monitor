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
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Сумма продаж:
                            <span>{{ number($summary['amount']) }} ₸</span>
                        </div>

                        <div class="box-title-action">
                            <span data-canvas="statistics-amount" class="btn is-sm is-outlined js-print-canvas">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </span>
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-amount" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Количество продаж:
                            <span>{{ number($summary['count']) }}</span>
                        </div>

                        <div class="box-title-action">
                            <span data-canvas="statistics-count" class="btn is-sm is-outlined js-print-canvas">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </span>
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-count" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>

                <div class="box is-marged">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Средний чек:
                            <span>{{ number($summary['avg']) }} ₸</span>
                        </div>

                        <div class="box-title-action">
                            <span data-canvas="statistics-avg" class="btn is-sm is-outlined js-print-canvas">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </span>
                        </div>
                    </div>

                    <div class="box-content">
                        <canvas id="statistics-avg" class="rounded-sm mb-16" height="80vh"></canvas>
                    </div>
                </div>

                @if (count($visits))
                    <div class="box is-marged">
                        <div class="box-title has-action">
                            <div class="box-title-text">
                                Посещения:
                                <span>{{ number($summary['visits']) }}</span>
                            </div>

                            <div class="box-title-action">
                                <span data-canvas="visits-count" class="btn is-sm is-outlined js-print-canvas">
                                    <i class="fa fa-file-pdf-o"></i>
                                    Скачать PDF
                                </span>
                            </div>
                        </div>

                        <div class="box-content">
                            <canvas id="visits-count" class="rounded-sm mb-16" height="80vh"></canvas>
                        </div>
                    </div>
                @endif

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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@1.5.3/dist/jspdf.min.js"></script>
    <script>
        $(function () {
            $('.js-print-canvas').on('click', function () {
                var newCanvas = document.querySelector('#' + $(this).data('canvas'));
                var newCanvasImg = newCanvas.toDataURL("image/png", 1.0);
                var doc = new jsPDF("l", "mm", "a4");
                doc.addImage(newCanvasImg, 'PNG', 5, 5, doc.internal.pageSize.getWidth() - 10, 0);
                doc.save('keruenmonitor-chart_' + Date.now() + '.pdf');
            });

        });
        Chart.defaults.global.legend.display = false;
        Chart.defaults.global.tooltips.callbacks.label = function (tooltipItem) {
            return addCommas(tooltipItem.yLabel);
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[ 0 ];
            x2 = x.length > 1 ? '.' + x[ 1 ] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        @if (count($stats))
        new Chart('statistics-amount', {
            type: 'line',
            data: {
                labels: @json($graphStats['labels']),
                datasets: [ {
                    label: 'Сумма продаж',
                    borderColor: '#38c172',
                    data: @json($graphStats['amount']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            }
        });

        new Chart('statistics-count', {
            type: 'line',
            data: {
                labels: @json($graphStats['labels']),
                datasets: [ {
                    label: 'Количество чеков',
                    borderColor: '#38c172',
                    data: @json($graphStats['count']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            }
        });

        new Chart('statistics-avg', {
            type: 'line',
            data: {
                labels: @json($graphStats['labels']),
                datasets: [ {
                    label: 'Средний чек',
                    borderColor: '#38c172',
                    data: @json($graphStats['avg']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            }
        });
        @endif

        @if (count($visits))
        new Chart('visits-count', {
            type: 'line',
            data: {
                labels: @json($graphVisits['labels']),
                datasets: [ {
                    label: 'Количество',
                    borderColor: '#38c172',
                    data: @json($graphVisits['count']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            }
        })
        @endif
    </script>
@endpush
