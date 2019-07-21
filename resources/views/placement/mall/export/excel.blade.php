@php /** @var array $statistics_current */ @endphp
@php /** @var array $statistics_past */ @endphp
@php /** @var array $mall_names */ @endphp
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .period {
            color: #666;
        }

        .is-right {
            text-align: right;
        }

        .is-success {
            color: green;
        }

        .is-danger {
            color: red;
        }

        .is-danger-background {
            background: #fcebea;
        }

        .is-success-background {
            background: #e3fcec;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <th style="background: #38c172; color: #ffffff;">
            <strong>Положение ТРЦ</strong>
        </th>
        <th nowrap colspan="3" style="background: #38c172; color: #ffffff; text-align: right;">
            Сравнение <strong> {{ $dates['current']['from'] }} - {{ $dates['current']['to'] }}</strong>
            с <strong>{{ $dates['past']['from'] }} - {{ $dates['past']['to'] }}</strong>
        </th>
    </tr>
    <tr>
        <th style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ (request()->get('mall_id')) ? \App\Models\Mall::find(request()->get('mall_id'))->name : 'Все' }}
        </th>
        <th colspan="3" style="background: #f0f0f0; font-weight: 400; text-align: right;">
            Заведение: {{ (request()->get('store_id')) ? \App\Models\Store::find(request()->get('store_id'))->name : 'Все' }}
        </th>
    </tr>
    <tr>
        <th>
            ТРЦ
        </th>
        <th style="text-align: right">
            Кол-во чек.
        </th>
        <th style="text-align: right">
            Сред. чек.
        </th>
        <th style="text-align: right">
            Сумма продаж
        </th>
    </tr>
    @foreach($mall_names as $mall_id => $mall_name)
        <tr>
            <td>
                {{ $mall_name }}
            </td>
            @include('placement.includes.placement-table-td', ['key' => 'count'])
            @include('placement.includes.placement-table-td', ['key' => 'avg'])
            @include('placement.includes.placement-table-td', ['key' => 'amount', 'currency' => true])
        </tr>
    @endforeach
</table>

</body>
</html>
