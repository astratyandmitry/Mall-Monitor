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
          @php $card = new App\Classes\Design\MallCard(@$stats[$mall->id], @$visits[$mall->id]) @endphp

          <a href="{{ $mall->link() }}" class="stores-item">
            <div class="stores-item-name">
              <span class="stores-item-name-text">{{ $mall->name }}</span>
            </div>

            <div class="stores-item-detail">
              <div class="stores-item-detail-label">
                Арендаторов: <span>{{ number(count($mall->stores)) }}</span>
              </div>
              <div class="stores-item-detail-text">
                Оборот за {{ $currentMonth }}: <span>{{ number($card->getChequesAmount()) }} ₸</span><br/>
                Посещений за {{ $currentMonth }}: <span>{{ number($card->getVisitsCount()) }}</span><br/>
                Конверсия за {{ $currentMonth }}: <span>{{ $card->getConversion() }}%</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@endsection
