@php /** @var array $stats */ @endphp
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
        <th colspan="2" style="background: #38c172; color: #ffffff; text-align: right;">
            {{ $selectedTime }}
        </th>
    </tr>
    <tr>
        <th colspan="5" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ $selectedMall }}
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
        <th>
            Дата
        </th>
    </tr>
    @foreach($stats as $stat)
        <tr>
            <td>
                {{ $mall_names[$stat['mall_id']] }}
            </td>
            <td>
                {{ (int)$stat['count'] }}
            </td>
            <td>
                {{ (int)round($stat['avg']) }}
            </td>
            <td>
                {{ (int)$stat['amount'] }}
            </td>
            <td>
                {{ \App\DateHelper::byDateGroup($stat['date'], $dateGroup) }}
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>
