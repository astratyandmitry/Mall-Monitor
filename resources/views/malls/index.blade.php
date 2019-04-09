@php /** @var array $statistics */ @endphp
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
                    @php $total = (isset($statistics[$mall->id])) ? number_format(round($statistics[$mall->id])) : 0 @endphp

                    <a href="{{ $mall->link() }}" class="stores-item">
                        <div class="stores-item-name">
                            <span class="stores-item-name-text">{{ $mall->name }}</span>
                        </div>

                        <div class="stores-item-detail">
                            <span class="stores-item-detail-text">
                                Текущая выручка: {{ $total }} ₸
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
