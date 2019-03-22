@php /** @var array $statistics */ @endphp
@php /** @var \App\Models\Store $store */ @endphp

@php $exportParams = request()->only(['date_from', 'date_to']) @endphp

@extends('layouts.app', $globals)

@section('content')
    <div class="heading">
        <div class="container">
            <div class="heading-content has-action">
                <div class="heading-text">
                    {{ $globals['title'] }}
                </div>

                <div class="heading-filter">
                    <div class="heading-filter-button">
                        <i class="fa fa-filter"></i>
                        <span>{{ isRequestEmpty() ? 'Показать' : 'Скрыть' }} фильтр</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="filter {{ isRequestEmpty() ? 'is-hidden' : '' }}">
        <div class="container">
            <form method="GET" class="filter-form">
                <div class="grid">
                    @include('layouts.includes.form.input', [
                        'attribute' => 'date_from',
                        'value' => request()->query('date_from'),
                        'label' => 'Дата начала',
                        'type' => 'datetime-local',
                    ])

                    @include('layouts.includes.form.input', [
                        'attribute' => 'date_to',
                        'value' => request()->query('date_to'),
                        'label' => 'Дата окончания',
                        'type' => 'datetime-local',
                    ])
                </div>

                <button type="submit" class="btn">Применить фильтр</button>
            </form>
        </div>
    </div>

    @if (count($statistics))
        <div class="content">
            <div class="container">
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            Статистика арендаторов
                        </div>

                        <div class="box-title-action">
                            <a href="{{ route('reports.store.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </a>

                            <a href="{{ route('reports.store.export.excel', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-excel-o"></i>
                                Скачать Excel
                            </a>
                        </div>
                    </div>

                    <div class="box-content">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>
                                    ТРЦ
                                </th>
                                <th>
                                    Арендатор
                                </th>
                                <th class="is-center" width="100">
                                    Кол-во
                                </th>
                                <th class="is-right" width="120">
                                    Сред. чек
                                </th>
                                <th class="is-right" width="160">
                                    Сумма
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $amount = 0 @endphp
                            @php $count = 0 @endphp
                            @foreach($statistics as $statistic)
                                @php $amount += $statistic['amount'] @endphp
                                @php $count += $statistic['count'] @endphp
                                @php $store = \App\Models\Store::find($statistic['store_id']) @endphp
                                <tr>
                                    <td>
                                        {{ $store->mall->name }}
                                    </td>
                                    <td>
                                        {{ $store->name }}
                                    </td>
                                    <td class="is-center">
                                        {{ number_format($statistic['count']) }}
                                    </td>
                                    <td class="is-right">
                                        {{ number_format(round($statistic['amount'] / $statistic['count'])) }} ₸
                                    </td>
                                    <td class="is-right">
                                        {{ number_format($statistic['amount']) }} ₸
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th class="is-center">
                                    {{ number_format($count) }}
                                </th>
                                <th class="is-right">
                                    {{ number_format(round($amount / $count)) }} ₸
                                </th>
                                <th class="is-right">
                                    {{ number_format($amount) }} ₸
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
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

@push('scripts')
    <script>
        $(function () {
            var $filter = $('.filter');
            var $filterToggle = $('.heading-filter-button');

            $filterToggle.on('click', function () {
                if ($filter.is(':visible')) {
                    $filterToggle.find('span').text('Показать фильтр');
                } else {
                    $filterToggle.find('span').text('Скрыть фильтр');
                }

                $filter.slideToggle(160);
            });
        });
    </script>
@endpush

