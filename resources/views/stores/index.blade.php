@php /** @var array $statistics */ @endphp
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

    @if (count($stores))
        <div class="content">
            <div class="container">
                <div class="stores">
                    @foreach($stores as $store)
                        @php $storeItem = new App\Classes\Design\StoreItem(@$statistics[$store->id], @$visits[$store->id]) @endphp

                        <a href="{{ $store->link() }}" class="stores-item {{ $store->is_errors_yesterday ? 'is-danger' : '' }}">
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
                                @if (!$store->is_errors_yesterday)
                                    <span class="stores-item-detail-text">
                                        Оборот за {{ $currentMonth }}: <strong>{{ number_format($storeItem->getChequesAmount()) }} ₸</strong><br/>
                                        Посещений за {{ $currentMonth }}: <strong>{{ number_format($storeItem->getVisitsCount()) }}</strong><br/>
                                        Конверсия за {{ $currentMonth }}: <strong>{{ $storeItem->getConversion() }}%</strong>
                                    </span>
                                @else
                                    <span class="stores-item-detail-text">
                                        Отсутствуют вчерашние транзакции
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
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
