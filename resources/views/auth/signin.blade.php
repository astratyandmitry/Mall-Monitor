@extends('layouts.clean')

@section('content')
    <div class="promo">
        <div class="container">
            <div class="promo-box">
                <div class="promo-box-brand">
                    <div class="promo-box-brand-logotype">
                        {{ config('app.name') }}
                    </div>
                </div>

                <form method="POST" class="promo-box-form">
                    @csrf

                    @include('layouts.includes.form.input', [
                        'attribute' => 'email',
                        'placeholder' => 'E-mail',
                        'autofocus' => true,
                        'required' => true,
                    ])

                    @include('layouts.includes.form.password', [
                        'attribute' => 'password',
                        'placeholder' => 'Пароль',
                        'required' => true,
                    ])

                    <button type="submit" class="btn">Войти в систему</button>
                </form>
            </div>
        </div>
    </div>
@endsection