<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title'){{config('app.name', 'Laravel')}}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="/images/logo.ico">
</head>
<body class="h-100 d-flex justify-content-center align-items-center">
    <div class="app app-only-content">
        {{--  ロゴ画像  --}}
        <div class="d-flex justify-content-center">
            <a href="{{route(top)}}"><img src="/images/logo-1.png"></a>
        </div>
        {{--  コンテンツ部分  --}}
        <div class="d-flex justify-content-center mt-3">
            <div class="col-auto">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
