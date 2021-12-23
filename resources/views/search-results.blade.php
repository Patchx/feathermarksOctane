<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script async src="https://cse.google.com/cse.js?cx=910818fda3106e175"></script>
</head>

<body>
    <div class="gcse-searchresults-only"></div>
</body>
</html>
