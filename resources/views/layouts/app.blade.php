<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@isset($title) {{ $title }} — @endisset{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:100,200,300,400,700&amp;subset=cyrillic-ext" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-grey-lightest font-sans pt-16">

<div id="app">
    @include('layouts.partials.header')

    <section class="my-16">
        <div class="px-4">
            @yield('content')
        </div>
    </section>

    @include('layouts.partials.footer')
</div>

<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>
