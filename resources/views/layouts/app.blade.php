<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@isset($title)
      {{ $title }} —
    @endisset{{ config('app.name') }}</title>

  <!-- Styles -->
  @stack('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet">
  <link href="{{ mix('css/jquery.datetimepicker.min.css') }}" rel="stylesheet">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="preload">

<div id="app">
  @include('layouts.partials.header')

  @include('layouts.partials.breadcrumbs')

  <div class="main">
    @yield('content')
  </div>

  @include('layouts.partials.footer')
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script>
  $(function () {
    $('.filter select').select2()
  })
</script>
@stack('scripts')

</body>
</html>
