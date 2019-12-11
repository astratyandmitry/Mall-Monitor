@php /** @var \App\Models\Cheque[] $cheques */ @endphp

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

    @include('reports.detail.partials.filter')

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
                        <table class="table" border="0" cellpadding="0" cellspacing="0" width="100%">
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
