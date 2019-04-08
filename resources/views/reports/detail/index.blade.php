@php /** @var \App\Models\Cheque[] $cheques */ @endphp
@php /** @var array $statistics */ @endphp
@php /** @var array $counts */ @endphp

@php $exportParams = request()->only(['date_from', 'time_from', 'date_to', 'time_to', 'store_id', 'mall_id', 'store_name', 'store_official', 'store_bin', 'sort_key', 'sort_value']) @endphp

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
                @include('layouts.includes.field.hidden', ['attribute' => 'sort_key', 'value' => 'created_at'])
                @include('layouts.includes.field.hidden', ['attribute' => 'sort_type', 'value' => 'desc'])

                @if ( ! $currentUser->store_id)
                    @if ($currentUser->mall_id)
                        @include('layouts.includes.form.dropdown', [
                            'attribute' => 'store_id',
                             'value' => request()->query('store_id'),
                            'placeholder' => 'Все',
                            'label' => 'Арендатор',
                            'options' => \App\Repositories\StoreRepository::getOptions($currentUser->mall_id),
                        ])
                    @else
                        <div class="grid">
                            @include('layouts.includes.form.dropdown', [
                                'attribute' => 'mall_id',
                                'value' => request()->query('mall_id'),
                                'label' => 'ТРЦ',
                                'placeholder' => 'Все',
                                'options' => \App\Repositories\MallRepository::getOptions(),
                            ])

                            @if (request()->get('mall_id'))
                                @include('layouts.includes.form.dropdown', [
                                   'attribute' => 'store_id',
                                    'value' => request()->query('store_id'),
                                   'placeholder' => 'Все',
                                   'label' => 'Арендатор',
                                   'options' => \App\Repositories\StoreRepository::getOptions(request()->get('mall_id')),
                               ])
                            @else
                                @include('layouts.includes.form.dropdown-grouped', [
                                   'attribute' => 'store_id',
                                   'placeholder' => 'Все',
                                   'value' => request()->query('store_id'),
                                   'label' => 'Арендатор',
                                   'options' => \App\Repositories\StoreRepository::getOptionsGrouped(),
                               ])
                            @endif
                        </div>
                    @endif

                    <div class="grid is-3">
                        @include('layouts.includes.form.input', [
                            'attribute' => 'store_name',
                            'value' => request()->query('store_name'),
                            'label' => 'Бренд',
                            'placeholder' => 'Любой',
                        ])

                        @include('layouts.includes.form.input', [
                            'attribute' => 'store_official',
                            'value' => request()->query('store_official'),
                            'label' => 'Юр. наименование',
                            'placeholder' => 'Любой',
                        ])

                        @include('layouts.includes.form.input', [
                            'attribute' => 'store_bin',
                            'value' => request()->query('store_bin'),
                            'label' => 'БИН',
                            'placeholder' => 'Любой',
                        ])
                    </div>
                @endif

                <div class="grid">
                    <div class="grid-sub">
                        @include('layouts.includes.form.input', [
                            'attribute' => 'date_from',
                            'value' => request()->query('date_from'),
                            'label' => 'Дата начала',
                            'type' => 'date',
                            'placeholder' => 'mm-dd-yyyy',
                        ])

                        @include('layouts.includes.form.input', [
                            'attribute' => 'time_from',
                            'value' => request()->query('time_from'),
                            'label' => 'Время начала',
                            'type' => 'time',
                            'placeholder' => 'HH:ii',
                        ])
                    </div>

                    <div class="grid-sub">
                        @include('layouts.includes.form.input', [
                             'attribute' => 'date_to',
                             'value' => request()->query('date_to'),
                             'label' => 'Дата окончания',
                             'type' => 'date',
                             'placeholder' => 'mm-dd-yyyy',
                         ])

                        @include('layouts.includes.form.input', [
                            'attribute' => 'time_to',
                            'value' => request()->query('time_to'),
                            'label' => 'Время окончания',
                            'type' => 'time',
                            'placeholder' => 'HH:ii',
                        ])
                    </div>
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
                            {{--<a href="{{ route('reports.detail.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">--}}
                            {{--<i class="fa fa-file-pdf-o"></i>--}}
                            {{--Скачать PDF--}}
                            {{--</a>--}}

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
                                <th nowrap>
                                    Арендатор
                                    @include('layouts.includes.table.sorting', ['attribute' => 'store_id', 'default_key' => 'created_at'])
                                </th>
                                <th nowrap width="160">
                                    Код касссы
                                    @include('layouts.includes.table.sorting', ['attribute' => 'kkm_code', 'default_key' => 'created_at'])
                                </th>
                                <th nowrap width="120">
                                    Док. №
                                    @include('layouts.includes.table.sorting', ['attribute' => 'number', 'default_key' => 'created_at'])
                                </th>
                                <th nowrap class="is-center" width="110">
                                    Кол-во поз.
                                </th>
                                <th nowrap width="160">
                                    Операция
                                    @include('layouts.includes.table.sorting', ['attribute' => 'type_id', 'default_key' => 'created_at'])
                                </th>
                                <th nowrap class="is-right" width="80">
                                    Сумма
                                    @include('layouts.includes.table.sorting', ['attribute' => 'amount', 'default_key' => 'created_at'])
                                </th>
                                <th nowrap class="is-right" width="140 ">
                                    Дата и время
                                    @include('layouts.includes.table.sorting', ['attribute' => 'created_at', 'default_key' => 'created_at', 'default_type' => 'desc'])
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $amount = 0 @endphp
                            @php $count = 0 @endphp
                            @foreach($cheques as $cheque)
                                <tr>
                                    <td nowrap>
                                        {{ $cheque->store->name }}
                                    </td>
                                    <td nowrap>
                                        {{ $cheque->kkm_code }}
                                    </td>
                                    <td nowrap>
                                        {{ $cheque->number }}
                                    </td>
                                    <td nowrap class="is-center">
                                        {{ isset($counts[$cheque->id]) ? number_format($counts[$cheque->id]) : 0 }}
                                    </td>
                                    <td nowrap>
                                        <div class="badge is-inline {{ $cheque->type->getCssClass() }}">
                                            {{ $cheque->type->name }}
                                        </div>
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ number_format($cheque->amount) }} ₸
                                    </td>
                                    <td nowrap class="is-right">
                                        {{ $cheque->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th nowrap colspan="4">
                                    Общее количество: {{ number_format($statistics['count']) }}
                                </th>
                                <th nowrap colspan="4" class="is-right">
                                    Общая сумма: {{ number_format($statistics['total']) }} ₸
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{ $cheques->appends(paginateAppends())->links('vendor.pagination.default') }}
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
            $storeID = $('#store_id');
            $cashboxID = $('#cashbox_id');

            $storeID.on('change', function () {
                $cashboxID.html('<option>Загрузка...</option>').attr('disabled', true);

                $.ajax({
                    method: 'POST',
                    url: '/ajax/cashboxes',
                    data: {
                        store_id: $storeID.val()
                    },
                    success: function (response) {
                        $cashboxID.html(response).attr('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
