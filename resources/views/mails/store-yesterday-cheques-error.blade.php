@component('mail::message')
# {{ $store->mall->name }}: {{ $store->name }} отсуствутют данные за {{ $date }}

Данные арендатора:
* ТРЦ: {{ $store->mall->name }}
* БИН: {{ spaces($store->business_identification_number) }}
@endcomponent
