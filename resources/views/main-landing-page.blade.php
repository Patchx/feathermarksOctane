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

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
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
        </style>
    </head>

    <body>
        <div class="flex-center">
            @if(Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        <a href="{{ route('register') }}">Sign Up</a>
                    @endauth
                </div>
            @endif

            <div class="text-center">
                <br><br><br>
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

        <br>

        <div class="full-width">
            <hr>
            <br>

            <h2 class="d-xs-block d-md-none font-thin mb-20 text-center">Never forget a bookmark</h2>

            <div 
                class="pl-30 pr-30"
                style="display:flex"
            >
                <h2 class="d-none d-md-block font-thin mr-30 mt-30 text-right">Never forget a bookmark</h2>
                
                <img 
                    class="full-width"
                    src="https://res.cloudinary.com/feathermarks-com/image/upload/v1647825363/root-page/feathermarks_gif_search_t8swtt.gif"
                />                
            </div>
            <br><br>
        </div>

        <hr>

        <br>
        
        <p class="blog-footer-feather mx-auto text-center text-muted">
            <i class="fas fa-feather-alt fa-3x"></i>
        </p>

        <footer class="pb-10">
            <p class="text-center text-muted">Copyright {{date('Y')}}, <a href="/">Feathermarks.com</a></p>
        </footer>
    </body>
</html>
