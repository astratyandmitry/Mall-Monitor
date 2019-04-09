@php /** @var array $statistics */ @endphp
@php /** @var string|null $dateFrom */ @endphp
@php /** @var string|null $dateTo */ @endphp
@php /** @var array $mall_names */ @endphp
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<table class="table" width="100%" border="1" cellspacing="0" cellpadding="8">
    <tr>
        <th colspan="2" style="background: #38c172; color: #ffffff;">
            <strong>Отчет по ТРЦ</strong>
        </th>
        <th colspan="2" style="background: #38c172; color: #ffffff; text-align: right;">
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
        <th colspan="4" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ (request()->has('mall_id')) ? \App\Models\Mall::find(request()->get('mall_id'))->name : 'Все' }}
        </th>
    </tr>
    <tr>
        <th>
            ТРЦ
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
