@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            @if (count($statistics))
                <div class="graphs">
                    <h2 class="mb-8 text-grey-darkest">
                        Сумма продаж
                    </h2>

                    <canvas id="statistics-amount" class="rounded-sm mb-16" height="80vh"></canvas>

                    <h2 class="mb-8 text-grey-darkest">
                        Количество продаж
                    </h2>

                    <canvas id="statistics-count" class="rounded-sm mb-16" height="80vh"></canvas>

                    <h2 class="mb-8 text-grey-darkest">
                        Средний чек
                    </h2>

                    <canvas id="statistics-avg" class="rounded-sm" height="80vh"></canvas>
                </div>

                <div class="statistics mt-16">
                    <h2 class="mb-8 text-grey-darkest">
                        Статистика последних дней
                    </h2>

                    <div class="pb-4 font-bold flex w-full">
                        <div class="pl-4 text-grey-darker w-full">
                            Дата
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            Количество
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            Средний чек
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            Сумма
                        </div>
                    </div>

                    @php $amount = 0 @endphp
                    @php $count = 0 @endphp
                    @foreach($statistics as $statistic)
                        @php $amount += $statistic->amount @endphp
                        @php $count += $statistic->count @endphp

                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            <div class="text-grey-darkest w-full pl-4">
                                <a class="text-grey-darkest no-underline border-b border-grey hover:border-transparent"
                                   href="{{ route('daily_report.index', ['date' => $statistic->date, 'store_id' => $store->id]) }}">
                                    {{ date('d.m.Y', strtotime($statistic->date)) }}
                                </a>
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format($statistic->count) }}
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format(round($statistic->amount / $statistic->count)) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-64 pr-4">
                                {{ number_format($statistic->amount) }} ₸
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm font-bold flex w-full py-4 bg-grey-light">
                        <div class="pl-4 text-grey-darker w-full"></div>

                        <div class="px-4 text-grey-darker w-48 px-4">
                            {{ number_format($count) }}
                        </div>

                        <div class="px-4 text-grey-darker w-48 px-4">
                            {{ number_format(round($amount / $count)) }} ₸
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            {{ number_format($amount) }} ₸
                        </div>
                    </div>
                </div>
            @endif

            @if (count($cheques))
                <div class="cheques mt-16">
                    <h2 class="mb-8 text-grey-darkest">
                        Последние тразакции за сегодня
                    </h2>

                    <div class="pb-4 font-bold flex w-full">
                        <div class="pl-4 text-grey-darker w-full">
                            Код касссы
                        </div>

                        <div class="px-4 text-grey-darker w-96">
                            Номер документа
                        </div>

                        <div class="px-4 text-grey-darker w-96">
                            Тип операции
                        </div>

                        <div class="px-4 text-grey-darker w-64 text-right">
                            Сумма
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-96">
                            Дата и время
                        </div>
                    </div>

                    @foreach($cheques as $cheque)
                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            <div class="w-full pl-4">
                                {{ $cheque->kkm_code }}
                            </div>

                            <div class="text-grey-darkest w-96 px-4">
                                {{ $cheque->number }}
                            </div>

                            <div class="text-grey-darkest w-96 px-4">
                                {{ $cheque->type->name }}
                            </div>

                            <div class="text-grey-darkest w-64 px-4 text-right">
                                {{ number_format($cheque->amount) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-96 pr-4">
                                {{ $cheque->created_at->format('d.m.Y H:i:s') }}
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm flex w-full py-4 bg-grey-light">
                        <div class="pr-4 text-grey-darker text-center w-full">
                            <a href="{{ route('daily_report.index', ['store_id' => $store->id]) }}"
                               class="text-grey-darkest no-underline border-b border-grey hover:border-transparent">
                                Получить полный отчет за {{ date('d.m.Y') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if (! count($statistics))
                <div class="bg-grey -mx-8 px-8 py-16 -mb-8 rounded-b">
                    <div class="text-white text-center text-lg font-light">
                        Информация по данному заведению отсутствует
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

        new Chart('statistics-amount', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Сумма продаж',
                    borderColor: '#2f365f',
                    data: @json($graph['amount']),
                } ]
            }
        });

        new Chart('statistics-count', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Количество чеков',
                    borderColor: '#2f365f',
                    data: @json($graph['count']),
                } ]
            }
        });

        new Chart('statistics-avg', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Средний чек',
                    borderColor: '#2f365f',
                    data: @json($graph['avg']),
                } ]
            }
        });
    </script>
@endpush
