@php /** @var array $statistics */ @endphp
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
                    @php $money = (isset($statistics[$mall->id])) ? number_format(round($statistics[$mall->id]->amount)) : 0 @endphp
                    @php $transactions = (isset($statistics[$mall->id])) ? $statistics[$mall->id]->count : 0 @endphp
                    @php $visit = (isset($visits[$mall->id])) ? $visits[$mall->id] : 0 @endphp
                    @php $calc = ($transactions > 0 && $visit > 0) ? $transactions * 100 / $visit : 0 @endphp

                    <a href="{{ $mall->link() }}" class="stores-item">
                        <div class="stores-item-name">
                            <span class="stores-item-name-text">{{ $mall->name }}</span>
                        </div>

                        <div class="stores-item-detail">
                            <span class="stores-item-detail-text">
                                Оборот за {{ mb_strtolower($currentMonth) }}: <strong>{{ $money }} ₸</strong><br/>
                                Посещений за {{ mb_strtolower($currentMonth) }}: <strong>{{ number_format($visit) }}</strong><br/>
                                Конверсия за {{ mb_strtolower($currentMonth) }}: <strong>{{ round($calc, 2) }}%</strong>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
