@php /** @var array $stats */ @endphp
@php /** @var array $visits */ @endphp
@php /** @var string|null $dateFrom */ @endphp
@php /** @var string|null $dateTo */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var string $selectedTime */ @endphp
@php /** @var string $selectedMall */ @endphp
  <!doctype html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<table>
  <tr>
    <th colspan="3" style="background: #38c172; color: #ffffff;">
      <strong>Отчет по ТРЦ</strong>
    </th>
    <th colspan="4" style="background: #38c172; color: #ffffff; text-align: right;">
      {{ $selectedTime }}
    </th>
  </tr>
  <tr>
    <th colspan="7" style="background: #f0f0f0; font-weight: 400;">
      ТРЦ: {{ $selectedMall }}
    </th>
  </tr>
  <tr>
    <th>
      ТРЦ
    </th>
    <th>
      Конверсия
    </th>
    <th>
      Посещений
    </th>
    <th>
      Кол-во чеков
    </th>
    <th>
      Средний чек
    </th>
    <th>
      Сумма чеков
    </th>
    <th>
      Дата
    </th>
  </tr>
  @php $tableTotal = new App\Classes\Design\ReportTableTotal @endphp
  @foreach($stats as $stat)
    @php $tableItem = new App\Classes\Design\ReportTableItem($stat, @$visits[$stat['date']][$stat['mall_id']]) @endphp
    @php $tableTotal->increase($tableItem) @endphp
    <tr>
      <td>
        {{ $mall_names[$stat['mall_id']] }}
      </td>
      <td>
        {{ $tableItem->getConversion() }} %
      </td>
      <td>
        {{ number($tableItem->getVisitsCount()) }}
      </td>
      <td>
        {{ number($tableItem->getChequesCount()) }}
      </td>
      <td>
        {{ number($tableItem->getChequesAvgAmount()) }}
      </td>
      <td>
        {{ number($tableItem->getChequesAmount()) }}
      </td>
      <td>
        {{ $tableItem->getDateFormatted() }}
      </td>
    </tr>
  @endforeach
</table>

</body>
</html>
