@php /** @var \App\Models\Cheque[] $cheques */ @endphp
@php /** @var string $selectedStore */ @endphp
@php /** @var string $selectedMall */ @endphp
@php /** @var string $selectedTime */ @endphp
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>

<table>
    <tr>
        <th colspan="6" style="background: #38c172; color: #ffffff;">
            <strong>Детальный отчет</strong>
        </th>
        <th colspan="5" style="background: #38c172; color: #ffffff; text-align: right;">
            {{ $selectedTime }}
        </th>
    </tr>
    <tr>
        <th colspan="6" style="background: #f0f0f0; font-weight: 400;">
            ТРЦ: {{ $selectedMall }}
        </th>
        <th colspan="5" style="background: #f0f0f0; font-weight: 400; text-align: right;">
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
                {{ (string)$cheque->store->business_identification_number }}
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
