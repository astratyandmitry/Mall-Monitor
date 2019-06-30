@component('mail::message')
# {{ $store->mall->name }}: {{ $store->name }} отсуствутют данные за {{ $date }}

Данные арендатора:
* ТРЦ: {{ $store->mall->name }}
* БИН: {{ $store->business_identification_number }}
@endcomponent
