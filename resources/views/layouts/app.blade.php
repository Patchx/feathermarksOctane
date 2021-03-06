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

    <link rel="preload" href="{{ mix('/wp/js/app.js') }}" as="script">

    @yield('head_unique')
</head>

<body>
    <div id="vue_app">
        <div class="mt-20"></div>

        <h5 class="ml-30 text-muted">
            <a 
                href="/home"
                style="
                    color: inherit;
                    text-decoration: none;
                "
                tabindex="-1"
            >FeatherMarks</a>
        </h5>

        <div class="text-right mr-30">
            @guest
                <a 
                    href="{{ route('login') }}"
                    class="mr-25"
                >{{ __('Login') }}</a>

                @if(Route::has('register'))
                    <a href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
            @else
                <div style="margin-top:-30px">
                    <h5 
                        class="cursor-pointer text-muted"
                        onclick="$('#hover-main-menu').toggle()"
                    >
                        <span>{{ucwords($active_category->name)}}&nbsp;</span>
                        <i class="fas fa-feather-alt fa-lg text-muted"></i>
                    </h5>

                    <section 
                        id="hover-main-menu"
                        class="text-left text-muted" 
                        style="display:none;"
                    >
                        <ul class="mb-10">
                            <li class="mb-30">
                                <p class="dropdown-label mb-5">Category</p>

                                <select 
                                    class="categories-dropdown form-control"
                                    onchange="window.location.href = '/home?cat_id=' + this.value" 
                                >
                                    @foreach($categories as $category)
                                        <option
                                            value="{{$category->custom_id}}"
                                            @if($category->custom_id === $active_category->custom_id)
                                                selected="true"
                                            @endif 
                                        >{{ucwords($category->name)}}</option>
                                    @endforeach
                                </select>
                            </li>

                            <li>
                                <p>
                                    <a
                                        class="text-muted"
                                        href="/categories"
                                    >Edit Categories</a>
                                </p>
                            </li>

                            <li class="mb-0">
                                <form 
                                    action="{{ route('logout') }}" 
                                    method="POST" 
                                    class="inline-block"
                                >
                                    @csrf

                                    <button
                                        class="btn btn-link ml-10 text-muted"
                                        style="
                                            padding: 0px;
                                            margin: 0px;
                                        "
                                        type="submit"
                                    >Logout</button>
                                </form>
                            </li>
                        </ul>
                    </section>
                </div>
            @endguest
        </div>

        <main class="mb-10">
            @yield('content')
        </main>
    </div>

    <script src="{{ mix('/wp/js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
