@php /** @var \App\Models\Cheque[] $cheques */ @endphp
@php /** @var array $statistics */ @endphp
@php /** @var array $counts */ @endphp

@php $exportParams = request()->only(['date_from', 'date_to', 'store_id', 'cashbox_id']) @endphp

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
                    @include('layouts.includes.form.dropdown', [
                        'placeholder' => 'Все',
                        'attribute' => 'store_id',
                        'value' => request()->query('store_id'),
                        'label' => 'Арендатор',
                        'options' => \App\Repositories\StoreRepository::getOptions(),
                    ])

                    @include('layouts.includes.form.dropdown', [
                        'placeholder' => 'Все',
                        'attribute' => 'cashbox_id',
                        'value' => request()->query('cashbox_id'),
                        'label' => 'Касса',
                        'options' => \App\Repositories\CashboxRepository::getOptionsForStore(request()->query('store_id')),
                    ])
                </div>

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

    @if (count($cheques))
        <div class="content">
            <div class="container">
                <div class="box">
                    <div class="box-title has-action">
                        <div class="box-title-text">
                            История транзакций
                        </div>

                        <div class="box-title-action">
                            <a href="{{ route('reports.detail.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">
                                <i class="fa fa-file-pdf-o"></i>
                                Скачать PDF
                            </a>

                            <a href="{{ route('reports.detail.export.excel', $exportParams) }}" class="btn is-sm is-outlined">
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
                                    Арендатор
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th>
                                    Код касссы
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th>
                                    Док. №
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th class="is-center" width="110">
                                    Кол-во поз.
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th width="160">
                                    Операция
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th class="is-right" width="80">
                                    Сумма
                                    <i class="fa fa-sort"></i>
                                </th>
                                <th class="is-right" width="140 ">
                                    Дата и время
                                    <i class="fa fa-sort-desc"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $amount = 0 @endphp
                            @php $count = 0 @endphp
                            @foreach($cheques as $cheque)
                                <tr>
                                    <td>
                                        {{ $cheque->store->name }}
                                    </td>
                                    <td>
                                        {{ $cheque->kkm_code }}
                                    </td>
                                    <td>
                                        {{ $cheque->number }}
                                    </td>
                                    <td class="is-center">
                                        {{ isset($counts[$cheque->id]['count']) ? number_format($counts[$cheque->id]['count']) : 0 }}
                                    </td>
                                    <td>
                                        <div class="badge is-inline {{ $cheque->type->getCssClass() }}">
                                            {{ $cheque->type->name }}
                                        </div>
                                    </td>
                                    <td class="is-right">
                                        {{ number_format($cheque->amount) }} ₸
                                    </td>
                                    <td class="is-right">
                                        {{ $cheque->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4">
                                    Общее количество: {{ number_format($statistics['count']) }}
                                </th>
                                <th colspan="4" class="is-right">
                                    Общая сумма: {{ number_format($statistics['total']) }} ₸
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{ $cheques->appends(getNotEmptyQueryParameters())->links('vendor.pagination.default') }}
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

            $storeID = $('#store_id');
            $cashboxID = $('#cashbox_id');

            $storeID.on('change', function () {
                $.ajax({
                    method: 'POST',
                    url: '/ajax/cashboxes',
                    data: {
                        store_id: $storeID.val()
                    },
                    success: function (response) {
                        $cashboxID.html(response);
                    }
                });
            });
        });
    </script>
@endpush
