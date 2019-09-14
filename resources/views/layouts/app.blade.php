<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.css" rel="stylesheet">

  <!-- Scripts -->
  <script>
    window.App = {!! json_encode([
      'user' => Auth::user(),
      'signedIn' => Auth::check()
    ]) !!}
  </script>

  <style>
    body { padding-bottom: 100px; }
    .level { display: flex; align-items: center; }
    .flex { flex: 1; }
    .mr-1 { margin-right: 1em; }
    .ml-a { margin-left: auto; }
    [v-cloak] { display: none; }
    .ais-highlight > em { background-color: yellow; font-style: normal; }
  </style>

  @yield('head')
</head>
<body>
  <div id="app">
    @include('layouts.nav')

    @yield('content')

    <flash :initial-data="{{ json_encode(['message' => session('flash'), 'level' => session('flash_level')]) }}"></flash>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}"></script>
  @yield('scripts')
</body>
</html>
