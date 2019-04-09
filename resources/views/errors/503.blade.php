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

                <div class="promo-box-form">
                    <div style="padding: 2em 0">
                        <h2>Ошибка 503</h2><br>

                        Сервер недоступен в данный момент. Попробуйте повторить попытку позднее
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection