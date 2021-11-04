<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($page_title))
        <title>{{ $page_title }}</title>
    @else
        <title>{{ config('app.name', 'UPLOADER') }}</title>
@endif

<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset_url('css/app.css') }}" rel="stylesheet">
</head>
<body>

<div class="wrapper" id="app">
    <div class="container">
        <h2 class="text-center py-4 mt-4 mb-5 header-title">{{ $page_title }}</h2>

        @yield('content')

    </div>

</div>


<!-- Scripts -->
<script src="{{ asset_url('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
