@component('mail::message')
# Активация аккаунта

Ваш почтовый адрес был указан при создании аккаунта в приложении {{ config('app.name')  }}.

Для того, что бы активировать аккаунт перейдите по ссылке ниже.

@component('mail::button', ['url' => route('auth.activate', ['email' => $user->email, 'activation_token' => $user->activation_token])])
    Активировать аккаунт
@endcomponent
@endcomponent