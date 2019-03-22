@php /** @var \stdClass[] $statistics */ @endphp
@php /** @var \App\Models\Cheque[] $cheques */ @endphp
@php /** @var array $pies */ @endphp
@php /** @var array $graph */ @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="box">
                <div class="box-title">
                    <div class="box-title-text">
                        Сумма продаж
                    </div>
                </div>

                <div class="box-content">
                    <canvas id="statistics-amount" class="rounded-sm mb-16" height="80vh"></canvas>
                </div>
            </div>

            <div class="box is-marged">
                <div class="box-title">
                    <div class="box-title-text">
                        Количество продаж
                    </div>
                </div>

                <div class="box-content">
                    <canvas id="statistics-count" class="rounded-sm mb-16" height="80vh"></canvas>
                </div>
            </div>

            <div class="box is-marged">
                <div class="box-title">
                    <div class="box-title-text">
                        Средний чек
                    </div>
                </div>

                <div class="box-content">
                    <canvas id="statistics-avg" class="rounded-sm mb-16" height="80vh"></canvas>
                </div>
            </div>

            <div class="box is-marged">
                <div class="box-title">
                    <div class="box-title-text">
                        Статистика последних дней
                    </div>
                </div>

                <div class="box-content">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>
                                Дата
                            </th>
                            <th class="is-center" width="100">
                                Кол-во
                            </th>
                            <th class="is-right" width="120">
                                Сред. чек
                            </th>
                            <th class="is-right" width="160">
                                Сумма
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $amount = 0 @endphp
                        @php $count = 0 @endphp
                        @foreach($statistics as $statistic)
                            @php $amount += $statistic->amount @endphp
                            @php $count += $statistic->count @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('reports.detail.index', ['date' => $statistic->date]) }}">
                                        {{ date('d.m.Y', strtotime($statistic->date)) }}
                                    </a>
                                </td>
                                <td class="is-center">
                                    {{ number_format($statistic->count) }}
                                </td>
                                <td class="is-right">
                                    {{ number_format(round($statistic->amount / $statistic->count)) }} ₸
                                </td>
                                <td class="is-right">
                                    {{ number_format($statistic->amount) }} ₸
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th class="is-center">
                                {{ number_format($count) }}
                            </th>
                            <th class="is-right">
                                {{ number_format(round($amount / $count)) }} ₸
                            </th>
                            <th class="is-right">
                                {{ number_format($amount) }} ₸
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if (count($cheques))
                <div class="box is-marged">
                    <div class="box-title">
                        <div class="box-title-text">
                            Сегодняшние транзакции
                        </div>
                    </div>

                    <div class="box-content">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    Арендатор
                                </th>
                                <th>
                                    Код касссы
                                </th>
                                <th>
                                    Док. №
                                </th>
                                <th class="is-center" width="100">
                                    Кол-во поз.
                                </th>
                                <th width="160">
                                    Операция
                                </th>
                                <th class="is-right" width="80">
                                    Сумма
                                </th>
                                <th class="is-right" width="140 ">
                                    Дата и время
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cheques as $cheque)
                                <tr>
                                    <td>
                                        {{ $cheque->store->name }}
                                    </td>
                                    <td>
                                        {{ $cheque->kkm_code }}
                                    </td>
                                    <td>
                                        {{ $cheque->number }}
                                    </td>
                                    <td class="is-center">
                                        {{ isset($counts[$cheque->id]['count']) ? number_format($counts[$cheque->id]['count']) : 0 }}
                                    </td>
                                    <td>
                                        <div class="badge is-inline {{ $cheque->type->getCssClass() }}">
                                            {{ $cheque->type->name }}
                                        </div>
                                    </td>
                                    <td class="is-right">
                                        {{ number_format($cheque->amount) }} ₸
                                    </td>
                                    <td class="is-right">
                                        {{ $cheque->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>
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

        @if (count($pies))
        new Chart('statistics-types', {
            type: 'pie',
            data: {
                datasets: [ {
                    data: @json($pies['totals']),
                    backgroundColor: @json($pies['colors'])
                } ],
                labels: @json($pies['names'])
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'right',
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
        @endif

        new Chart('statistics-amount', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Сумма продаж',
                    borderColor: '#38c172',
                    data: @json($graph['amount']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

        new Chart('statistics-count', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Количество чеков',
                    borderColor: '#38c172',
                    data: @json($graph['count']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

        new Chart('statistics-avg', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Средний чек',
                    borderColor: '#38c172',
                    data: @json($graph['avg']),
                } ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endpush
