@php /** @var array $statistics */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var array $stores */ @endphp
@php /** @var string|null $dateFrom */ @endphp
@php /** @var string|null $dateTo */ @endphp
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
            <strong>Отчет по арендаторам</strong>
        </th>
        <th colspan="3" style="background: #38c172; color: #ffffff; text-align: right;">
            @if ($dateFrom && $dateTo)
                c {{ date('d.m.Y H:i', strtotime($dateFrom)) }}
                по {{ date('d.m.Y H:i', strtotime($dateTo)) }}
            @elseif ($dateFrom)
                c {{ date('d.m.Y H:i', strtotime($dateFrom)) }}
            @elseif ($dateTo)
                по {{ date('d.m.Y H:i', strtotime($dateTo)) }}
            @else
                За все время
            @endif
        </th>
    </tr>
    <tr>
        <th colspan="3" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ (request()->has('mall_id')) ? \App\Models\Mall::find(request()->get('mall_id'))->name : 'Все' }}
        </th>
        <th colspan="3" style="background: #f0f0f0; font-weight: 400; text-align: right;">
            Заведение: {{ (request()->has('store_id')) ? \App\Models\Store::find(request()->get('store_id'))->name : 'Все' }}
        </th>
    </tr>
    <tr>
        <th>
            ТРЦ
        </th>
        <th>
            Заведение
        </th>
        <th>
            БИН
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
    </tr>
    @foreach($statistics as $statistic)
        <tr>
            <td>
                {{ $mall_names[$statistic['mall_id']] }}
            </td>
            <td>
                {{ $stores[$statistic['store_id']]['name'] }}
            </td>
            <td>
                {{ $stores[$statistic['store_id']]['business_identification_number'] }}
            </td>
            <td>
                {{ (int)$statistic['count'] }}
            </td>
            <td>
                {{ (int)round($statistic['avg']) }}
            </td>
            <td>
                {{ (int)$statistic['amount'] }}
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>
