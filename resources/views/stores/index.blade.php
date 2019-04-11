@php /** @var array $statistics */ @endphp
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
                        @php $total = (isset($statistics[$store->id])) ? number_format(round($statistics[$store->id])) : 0 @endphp

                        <a href="{{ $store->link() }}" class="stores-item {{ $store->is_errors_yesterday ? 'is-danger' : '' }}">
                            <div class="stores-item-name">
                                <span class="stores-item-name-text">{{ $store->name }}</span>
                            </div>

                            <div class="stores-item-detail">
                            <span class="stores-item-detail-text">
                                @if (!$store->is_errors_yesterday)
                                    Текущая выручка: {{ $total }} ₸
                                @else
                                    Отсутствуют вчерашние транзакции
                                @endif
                            </span>
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
