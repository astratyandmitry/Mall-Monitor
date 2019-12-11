@php /** @var array $stats */ @endphp
@php /** @var array $mall_names */ @endphp
@php /** @var array $stores */ @endphp
@php /** @var string $selectedTime */ @endphp
@php /** @var string $selectedMall */ @endphp
@php /** @var string $selectedStore */ @endphp
        <!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<table class="table" width="100%" border="1" cellspacing="0" cellpadding="8">
    <tr>
        <th colspan="3" style="background: #38c172; color: #ffffff;">
            <strong>Отчет по арендаторам</strong>
        </th>
        <th colspan="4" style="background: #38c172; color: #ffffff; text-align: right;">
            {{ $selectedTime }}
        </th>
    </tr>
    <tr>
        <th colspan="4" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ $selectedMall }}
        </th>
        <th colspan="3" style="background: #f0f0f0; font-weight: 400; text-align: right;">
            Заведение: {{ $selectedStore }}
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
                {{ $stores[$stat['store_id']]['name'] }}
            </td>
            <td>
                {{ (string)$stores[$stat['store_id']]['business_identification_number'] }}
            </td>
            <td>
                {{ number_format($stat['count']) }}
            </td>
            <td>
                {{ number_format(round($stat['avg'])) }}
            </td>
            <td>
                {{ number_format($stat['amount']) }}
            </td>
            <td>
                {{ \App\DateHelper::byDateGroup($stat['date']) }}
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>
