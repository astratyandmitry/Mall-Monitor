@php /** @var array $stats */ @endphp
@php /** @var array $visits */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var array $store_names */ @endphp

@php $exportParams = request()->only(['mall_id', 'date_from', 'time_from', 'date_to', 'time_to', 'sort_key', 'sort_value']) @endphp

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

  @include('reports.mall.partials.filter')

  @if (count($stats))
    <div class="content">
      <div class="container">
        <div class="box">
          <div class="box-title has-action">
            <div class="box-title-text">
              Статистика ТРЦ
            </div>

            <div class="box-title-action">
              <a href="{{ route('reports.mall.export.pdf', $exportParams) }}" class="btn is-sm is-outlined">
                <i class="fa fa-file-pdf-o"></i>
                Скачать PDF
              </a>

              <a href="{{ route('reports.mall.export.excel', $exportParams) }}" class="btn is-sm is-outlined">
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
                  ТРЦ
                  @include('layouts.includes.table.sorting', ['attribute' => 'mall_id', 'default_key' => 'mall_id'])
                </th>
                <th nowrap class="is-center" width="100">
                  Конверсия
                </th>
                <th nowrap class="is-center" width="100">
                  Посещений
                </th>
                <th nowrap class="is-center" width="100">
                  Кол-во чек.
                  @include('layouts.includes.table.sorting', ['attribute' => 'count', 'default_key' => 'mall_id'])
                </th>
                <th nowrap class="is-right" width="120">
                  Сред. чек.
                  @include('layouts.includes.table.sorting', ['attribute' => 'avg', 'default_key' => 'mall_id'])
                </th>
                <th nowrap class="is-right" width="160">
                  Сумма продаж
                  @include('layouts.includes.table.sorting', ['attribute' => 'amount', 'default_key' => 'mall_id'])
                </th>
                <th nowrap class="is-right" width="140">
                  Дата
                  @include('layouts.includes.table.sorting', ['attribute' => 'created_at', 'default_key' => 'created_at', 'default_type' => 'desc'])
                </th>
              </tr>
              </thead>
              <tbody>
              @php $tableTotal = new App\Classes\Design\ReportTableTotal @endphp
              @foreach($stats as $stat)
                @php $tableItem = new App\Classes\Design\ReportTableItem($stat, @$visits[$stat['date']][$stat['mall_id']]) @endphp
                @php $tableTotal->increase($tableItem) @endphp
                <tr>
                  <td nowrap>
                    {{ $mall_names[$stat['mall_id']] }}
                  </td>
                  <td nowrap class="is-center">
                    {{ $tableItem->getConversion() }} %
                  </td>
                  <td nowrap class="is-center">
                    {{ number($tableItem->getVisitsCount()) }}
                  </td>
                  <td nowrap class="is-center">
                    {{ number($tableItem->getChequesCount()) }}
                  </td>
                  <td nowrap class="is-right">
                    {{ number($tableItem->getChequesAvgAmount()) }} ₸
                  </td>
                  <td nowrap class="is-right">
                    {{ number($tableItem->getChequesAmount()) }} ₸
                  </td>
                  <td nowrap class="is-right" width="140 ">
                    {{ $tableItem->getDateFormatted() }}
                  </td>
                </tr>
              @endforeach
              </tbody>
              <tfoot>
              <tr>
                <th colspan="2" style="text-align: right">Итого:</th>
                <th nowrap class="is-center">
                  {{ number($tableTotal->getCountVisits()) }}
                </th>
                <th nowrap class="is-center">
                  {{ number($tableTotal->getChequesCount()) }}
                </th>
                <th nowrap class="is-right">
                  {{ number($tableTotal->getChequesAvgAmount()) }} ₸
                </th>
                <th nowrap class="is-right">
                  {{ number($tableTotal->getChequesAmount()) }} ₸
                </th>
                <th></th>
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
