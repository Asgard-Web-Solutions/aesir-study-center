<!doctype html>
<html data-theme="fantasy" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @vite('resources/css/app.css')

    <script src="https://kit.fontawesome.com/ac81ff684d.js" crossorigin="anonymous"></script>
</head>
<body >
    <div id="app">
        <nav class="navbar bg-base-100 text-primary">
            <div class="flex-1">
                <a href="{{ url('/') }}" class="text-xl normal-case btn btn-ghost"">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="flex-none">
                <ul class="px-1 menu menu-horizontal">
                    @guest
                        <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>

                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @endif
                    @else
                        <li><a href="{{ route('tests') }}" >Take Test</a></li>

                        <li tabindex="0">
                            <a>{{ Auth::user()->name }} <i class="fa-regular fa-angle-down"></i></a>
                            <ul class="p-2 bg-base-200">
                                <li><a href="{{ route('home') }}">{{ __('Profile') }}</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>

        <main>
            <br />
            @include('sweetalert::alert')

            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    @vite('resources/js/app.js')
</body>
</html>
