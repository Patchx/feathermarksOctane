<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FeatherMarks')</title>

    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito" as="font">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="preload" href="{{ mix('/wp/css/app.css') }}" as="style">
    <link href="{{ mix('/wp/css/app.css') }}" rel="stylesheet">
</head>

<body>
    @yield('content')

    <footer class="mt-30">
        <hr>
        <p class="text-center text-muted">Page created with <a href="/">FeatherMarks.com</a></p>
    </footer>
</body>
</html>
