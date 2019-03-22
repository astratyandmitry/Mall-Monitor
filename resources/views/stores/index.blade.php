@php /** @var array $statistics */ @endphp
@php /** @var \App\Models\Store[] $store */ @endphp

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
                @foreach($stores as $store)
                    <a href="{{ $store->link() }}" class="stores-item">
                        <div class="stores-item-name">
                            <span class="stores-item-name-text">{{ $store->name }}</span>
                        </div>

                        <div class="stores-item-detail">
                            <span class="stores-item-detail-text">Текущая выручка: {{ (isset($statistics[$store->id])) ? number_format(round($statistics[$store->id])) : 0 }}
                                ₸</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
