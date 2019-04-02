@php /** @var \App\Models\Cheque[] $cheques */ @endphp
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
        <th colspan="5" style="background: #38c172; color: #ffffff;">
            <strong>Детальный отчет</strong>
        </th>
        <th colspan="5" style="background: #38c172; color: #ffffff; text-align: right;">
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
        <th colspan="5" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ (request()->has('mall_id')) ? \App\Models\Mall::find(request()->get('mall_id'))->name : 'Все' }}
        </th>
        <th colspan="5" style="background: #f0f0f0; font-weight: 400; text-align: right;">
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
            Код касссы
        </th>
        <th>
            Номер документа
        </th>
        <th>
            Тип операции
        </th>
        <th>
            Вид оплаты
        </th>
        <th>
            Сумма
        </th>
        <th>
            Кол-во позиций
        </th>
        <th>
            Сумма позиций
        </th>
        <th>
            Дата и время
        </th>
    </tr>
    @foreach($cheques as $cheque)
        <tr>
            <td>
                {{ $cheque->store->mall->name }}
            </td>
            <td>
                {{ $cheque->store->name }}
            </td>
            <td>
                {{ $cheque->kkm_code }}
            </td>
            <td>
                {{ $cheque->number }}
            </td>
            <td>
                {{ $cheque->type->name }}
            </td>
            <td>
                {{ $cheque->payment->name }}
            </td>
            <td>
                {{ $cheque->amount }}
            </td>
            <td>
                {{ count($cheque->items) ? $cheque->items->count() : 0 }}
            </td>
            <td>
                {{ count($cheque->items) ? (int)$cheque->items->sum('quantity') : 0 }}
            </td>
            <td>
                {{ $cheque->created_at->format('d.m.Y H:i') }}
            </td>
        </tr>
    @endforeach
</table>

</body>
</html>
