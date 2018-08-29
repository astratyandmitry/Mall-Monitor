@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            @if (count($statistics))
                <canvas id="statistics" class="rounded-sm mb-8" height="80vh"></canvas>

                <div class="statistics">
                    <h2 class="mb-4">
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
                            Сумма
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-48">
                            Комиссия {{ $store->commission }}%
                        </div>
                    </div>

                    @php $amount = 0 @endphp
                    @php $count = 0 @endphp
                    @foreach($statistics as $statistic)
                        @php $amount += $statistic->amount @endphp
                        @php $count += $statistic->count @endphp

                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            <div class="text-grey-darkest w-full pl-4">
                                {{ date('d.m.Y', strtotime($statistic->date)) }}
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format($statistic->count) }}
                            </div>

                            <div class="text-grey-darkest w-48 px-4">
                                {{ number_format($statistic->amount) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-48 pr-4">
                                {{ number_format(($store->commission / 100) * $statistic->amount) }} ₸
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm font-bold flex w-full py-4 bg-grey-light">
                        <div class="pl-4 text-grey-darker w-full"></div>

                        <div class="px-4 text-grey-darker w-48">
                            {{ number_format($count) }}
                        </div>

                        <div class="px-4 text-grey-darker w-48">
                            {{ number_format($amount) }} ₸
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-48">
                            {{ number_format(($store->commission / 100) * $amount) }} ₸
                        </div>
                    </div>
                </div>
            @endif

            @if (count($cheques))
                <div class="cheques mt-8">
                    <h2 class="mb-4">
                        Сегодняшние транзакции
                    </h2>

                    <div class="mb-2 font-bold flex w-full py-2">
                        <div class="text-grey-darker w-full">
                            Код транзакции
                        </div>

                        <div class="text-grey-darker w-64">
                            Дата
                        </div>

                        <div class="text-grey-darker w-48">
                            Сумма
                        </div>

                        <div class="text-grey-darker text-right w-48">
                            Комиссия {{ $store->commission }}%
                        </div>
                    </div>

                    @foreach($cheques as $cheque)
                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter">
                            <div class="text-grey-darkest w-full pl-2">
                                {{ $cheque->code }}
                            </div>

                            <div class="text-grey-darkest w-64 px-2">
                                {{ $cheque->created_at->format('d.m.Y H:i') }}
                            </div>

                            <div class="text-grey-darkest w-48 px-2">
                                {{ number_format($cheque->amount) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-48 pr-2">
                                {{ number_format(($store->commission / 100) * $cheque->amount) }} ₸
                            </div>
                        </div>
                    @endforeach

                    {{ $cheques->links('vendor.pagination.default') }}
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

        new Chart('statistics', {
            type: 'line',
            data: {
                labels: @json($graph['labels']),
                datasets: [ {
                    label: 'Сумма продаж',
                    borderColor: '#2f365f',
                    data: @json($graph['values']),
                } ]
            }
        });
    </script>
@endpush
