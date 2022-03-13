<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FeatherMarks')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('/wp/css/app.css') }}" rel="stylesheet">

    @yield('head_unique')
</head>

<body>
    <div id="vue_app">
        <div class="mt-20"></div>

        <h5 class="ml-30 text-muted float-left">
            <a href="/">FeatherMarks</a>
        </h5>

        <div class="text-right mr-30">
            @guest
                <div class="float-right">
                    <a 
                        href="{{ route('login') }}"
                        class="mr-25"
                    >{{ __('Login') }}</a>

                    @if(Route::has('register'))
                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                </div>
            @else
                <a 
                    href="/home"
                    class="mr-25 float-right"
                >Dashboard</a>
            @endguest
        </div>

        <div class="clearfix"></div>

        <main class="mb-10">
            @yield('content')
        </main>

        <footer>
            <p class="text-center text-muted">Copyright {{date('Y')}}, <a href="https://feathermarks.com">Feathermarks.com</a></p>
        </footer>
    </div>
</body>
</html>
