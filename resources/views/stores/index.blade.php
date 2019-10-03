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
                        @php $money = (isset($statistics[$store->id])) ? number_format(round($statistics[$store->id]->amount)) : 0 @endphp
                        @php $transactions = (isset($statistics[$store->id])) ? $statistics[$store->id]->count : 0 @endphp
                        @php $visit = (isset($visits[$store->id])) ? $visits[$store->id] : 0 @endphp
                        @php $calc = ($transactions > 0 && $visit > 0) ? $transactions * 100 / $visit : 0 @endphp

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
                                        Оборот за {{ mb_strtolower($currentMonth) }}: <strong>{{ $money }} ₸</strong><br/>
                                        Посещений за {{ mb_strtolower($currentMonth) }}: <strong>{{ number_format($visit) }}</strong><br/>
                                        Конверсия за {{ mb_strtolower($currentMonth) }}: <strong>{{ round($calc, 2) }}%</strong>
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
