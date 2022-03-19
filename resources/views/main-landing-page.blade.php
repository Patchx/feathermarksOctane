<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FeatherMarks</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ mix('/wp/css/app.css') }}" rel="stylesheet">

        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .content {
                text-align: center;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .full-height {
                height: 100vh;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .position-ref {
                position: relative;
            }

            .subtitle {
                font-size: 25px;
                font-weight: 100;
            }

            .title {
                font-size: 45px;
                font-weight: 100;
            }

            .title-tagline-content {
                max-width: 600px;
                padding: 0px 20px;
            }

            .title-tagline-content h2 {
                font-size: 20px;
                font-weight: 100;
                padding: 0px 20px;
            }

            @media screen and (min-width: 600px) {
                .subtitle {
                    font-size: 30px;
                }

                .title {
                    font-size: 60px;
                }

                .title-tagline-content {
                    padding: 0px 10px 0px 80px;
                }

                .title-tagline-content h2 {
                    font-size: 25px;
                    padding: 0px;
                }
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        <a href="{{ route('register') }}">Sign Up</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1 class="title">Feather Marks <i class="fas fa-feather-alt"></i></h1>

                <h2 class="subtitle">Bookmarks, but better</h2>

                <br>

                <div class="title-tagline-content">
                    <h2 class="text-left">Fast, smart and totally free. Feathermarks is the power bookmarking tool that lets you move fast and keeps you organized - no matter how many bookmarks you have.</h2>

                    <br>

                    <a
                        class="btn btn-outline-primary"
                        href="/register"
                        style="width: 200px"
                    >Get Started</a>
                </div>
            </div>
        </div>
    </body>
</html>
