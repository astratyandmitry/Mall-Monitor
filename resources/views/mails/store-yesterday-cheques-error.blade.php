@component('mail::message')
# {{ $store->mall->name }}: {{ $store->name }} отсуствутют данные за {{ $date }}

Данные арендатора:
* БИН: {{ $store->mall->name }}
* ТРЦ: {{ $store->business_identification_number }}
@endcomponent
