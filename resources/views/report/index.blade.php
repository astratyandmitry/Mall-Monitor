@extends('layouts.app', $globals)

@section('content')
    <div class="shadow-lg rounded">
        <div class="p-8">
            <h1 class="mb-8">
                {{ $currentMall->name }}
                <span class="text-grey-darker font-normal">/ {{ $globals['title'] }}</span>
            </h1>

            @if (count($statistics))
                <div class="statistics">
                    <div class="pb-4 font-bold flex w-full">
                        <div class="pl-4 text-grey-darker w-full">
                            Заведение
                        </div>

                        <div class="px-4 text-grey-darker w-64">
                            Количество
                        </div>

                        <div class="px-4 text-grey-darker w-64">
                            Сумма
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            Коммиссия
                        </div>
                    </div>

                    @php $amount = 0 @endphp
                    @php $count = 0 @endphp
                    @php $commission = 0 @endphp
                    @foreach($statistics as $statistic)
                        @php $store = \App\Models\Store::find($statistic->store_id) @endphp
                        @php $amount += $statistic->amount @endphp
                        @php $count += $statistic->count @endphp
                        @php $commission += ($store->commission / 100) * $statistic->amount @endphp

                        <div class="border-t border-grey-lighter flex w-full py-4 hover:bg-grey-lighter hover:rounded-sm hover:border-transparent">
                            <div class="text-grey-darkest w-full pl-4">
                                {{ $store->name }}
                            </div>

                            <div class="text-grey-darkest w-64 px-4">
                                {{ number_format($statistic->count) }}
                            </div>

                            <div class="text-grey-darkest w-64 px-4">
                                {{ number_format($statistic->amount) }} ₸
                            </div>

                            <div class="text-grey-darkest text-right w-64 pr-4">
                                {{ number_format(($store->commission / 100) * $statistic->amount) }} ₸
                                <span class="text-grey-dark"> / {{ $store->commission }}%</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-sm font-bold flex w-full py-4 bg-grey-light">
                        <div class="pl-4 text-grey-darker w-full"></div>

                        <div class="px-4 text-grey-darker w-64">
                            {{ number_format($count) }}
                        </div>

                        <div class="px-4 text-grey-darker w-64">
                            {{ number_format($amount) }} ₸
                        </div>

                        <div class="pr-4 text-grey-darker text-right w-64">
                            {{ number_format($commission) }} ₸
                        </div>
                    </div>
                </div>
            @endif

            @if (! count($statistics))
                <div class="bg-grey -mx-8 px-8 py-16 -mb-8 rounded-b">
                    <div class="text-white text-center text-lg font-light">
                        Информация по заведениям отсутствует
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
