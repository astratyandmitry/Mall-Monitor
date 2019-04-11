@component('mail::message')
# {{ $store->mall->name }}: {{ $store->name }} отсуствутют данные за {{ $date }}

Данные арендатора:
* **

@component('mail::button', ['url' => route('auth.activate', ['email' => $user->email, 'activation_token' => $user->activation_token])])
    Активировать аккаунт
@endcomponent
@endcomponent