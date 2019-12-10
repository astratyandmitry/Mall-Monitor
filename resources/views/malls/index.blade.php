@php /** @var array $stats */ @endphp
@php /** @var array $visits */ @endphp
@php /** @var \App\Models\Mall[] $malls */ @endphp

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

    <div class="content">
        <div class="container">
            <div class="stores">
                @foreach($malls as $mall)
                    @php $mallItem = new App\Classes\Design\MallItem(@$stats[$mall->id], @$visits[$mall->id]) @endphp

                    <a href="{{ $mall->link() }}" class="stores-item">
                        <div class="stores-item-name">
                            <span class="stores-item-name-text">{{ $mall->name }}</span>
                        </div>

                        <div class="stores-item-detail">
                            <span class="stores-item-detail-text">
                                Оборот за {{ $currentMonth }}: <strong>{{ number_format($mallItem->getChequesAmount()) }} ₸</strong><br/>
                                Посещений за {{ $currentMonth }}: <strong>{{ number_format($mallItem->getVisitsCount()) }}</strong><br/>
                                Конверсия за {{ $currentMonth }}: <strong>{{ $mallItem->getConversion() }}%</strong>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
