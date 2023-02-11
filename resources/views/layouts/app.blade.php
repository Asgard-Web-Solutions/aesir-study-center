<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <script src="https://kit.fontawesome.com/ac81ff684d.js" crossorigin="anonymous"></script>
</head>
<body class="h-screen antialiased leading-none bg-gray-100">
    <div id="app">
        <nav class="py-6 mb-8 bg-blue-900 shadow">
            <div class="container px-6 mx-auto md:px-0">
                <div class="flex items-center justify-center">
                    <div class="mr-6">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <div class="flex-1 text-right">
                        @guest
                            <a class="p-3 text-sm text-gray-300 no-underline hover:underline" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a class="p-3 text-sm text-gray-300 no-underline hover:underline" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @else
                            @if ( auth()->user()->hasRole('admin') )
                                <a href="{{ route('manage-exams') }}" class="pr-4 text-sm text-gray-300">Manage Exams</a>
                            @endif

                            <a href="{{ route('tests') }}" class="pr-4 text-sm text-gray-300">Take Test</a>

                            <span class="pr-4 text-sm text-gray-300"><a href="{{ route('home') }}">{{ Auth::user()->name }}</a></span>

                            <a href="{{ route('logout') }}"
                               class="p-3 text-sm text-gray-300 no-underline hover:underline"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                            </form>                            
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        @include('sweetalert::alert')

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
