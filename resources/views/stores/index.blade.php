@php /** @var array $stats */ @endphp
@php /** @var array $visits */ @endphp
@php /** @var \App\Models\Store[] $stores */ @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="summary">
        <div class="container">
            <div class="summary-grid is-2">
                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number($stores->pluck('mall_id', 'mall_id')->count()) }}
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            ТРЦ
                        </div>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-item-value">
                        <div class="summary-item-value-text">
                            {{ number(count($stores)) }}
                        </div>
                    </div>

                    <div class="summary-item-label">
                        <div class="summary-item-label-text">
                            Арендаторы
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($stores))
        <div class="content">
            @php $storesByMall = $stores->groupBy('mall_id') @endphp

            @foreach($storesByMall as $mallId => $_stores)
                <div class="heading">
                    <div class="container">
                        <div class="heading-content">
                            <div class="heading-text is-sm">
                                <div style="float: right; opacity: .64">{{ number(count($_stores)) }} аренд.</div>
                                {{ $_stores[0]->mall->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="stores">
                        @foreach($_stores as $store)
                            @php $card = new App\Classes\Design\StoreCard(@$stats[$store->id], @$visits[$store->id]) @endphp

                            <a href="{{ $store->link() }}" class="stores-item">
                                @if ($store->rentable_area)
                                    <div class="stores-item-area">
                                    <span class="stores-item-area-text">
                                        {{ $store->rentable_area }} м²
                                    </span>
                                    </div>
                                @endif

                                <div class="stores-item-name">
                                    <span class="stores-item-name-text">{{ $store->name }}</span>
                                </div>

                                <div class="stores-item-detail">
                                    <div class="stores-item-detail-text">
                                        Оборот за {{ $currentMonth }}: <strong>{{ number($card->getChequesAmount()) }} ₸</strong><br/>
                                        Посещений за {{ $currentMonth }}: <strong>{{ number($card->getVisitsCount()) }}</strong><br/>
                                        Конверсия за {{ $currentMonth }}: <strong>{{ $card->getConversion() }}%</strong>
                                    </div>
                                    @if ($store->is_errors_yesterday)
                                        <div class="stores-item-detail-error">
                                            Отсутствуют вчерашние транзакции
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
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
