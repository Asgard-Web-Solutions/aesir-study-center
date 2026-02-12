<!DOCTYPE html>
<html lang="en" data-theme="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Study App') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/css/app.css')
</head>
<body class="text-gray-900 base-100">
    <div class="container mx-auto">
        <!-- Navbar -->
        <nav class="rounded-b-lg shadow-md navbar bg-neutral text-neutral-content">

            <details class="dropdown lg:hidden">
                <summary role="button" class="btn btn-ghost btn-circle">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h7" />
                  </svg>
                </summary>
                <ul class="menu menu-md dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow" style="z-index: 1000;">
                    @auth
                        <x-button.mobile href="{{ route('profile.index') }}">Profile Settings</x-button.mobile>
                        <x-button.mobile href="{{ route('profile.exams') }}">Your Library</x-button.mobile>
                        <x-button.mobile href="{{ route('exam.index') }}">Create/Manage Exams</x-button.mobile>
                        <x-button.mobile href="{{ route('profile.view', auth()->user()) }}">Your Transcripts</x-button.mobile>
                        <div class="divider"></div>
                    @endauth

                    <x-button.mobile href="{{ route('exam.public') }}">Public Exams</x-button.mobile>
                    <x-button.mobile href="{{ config('academy.discord_url') }}">Discord & Help</x-button.mobile>
                    <div class="divider"></div>

                    @guest
                        <x-button.mobile href="{{ route('login') }}">Login</x-button.mobile>
                        <x-button.mobile href="{{ route('register') }}">Register</x-button.mobile>
                    @endguest

                    @auth
                        @if ( auth()->user()->isAdmin )
                            <x-button.mobile href="{{ route('admin.index') }}">Admin Control Panel</x-button.mobile>
                        @endif

                        <a href="{{ route('logout') }}" class="block px-4 py-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                    @endauth
                </ul>
            </details>

            {{-- Main Navigation --}}
            <div class="container flex items-center justify-between px-4 mx-auto">
                <div class="px-2 bg-gray-100 rounded-xl">
                    <a @auth href="{{ route('profile.exams') }}" @endauth @guest href="{{ route('home') }}" @endguest class="text-lg font-semibold">
                        <img src="{{ asset('images/AALogo-DodgerBlue-Long-Small.png') }}" alt="Acolyte Academy Logo" style="height: 70px;" />
                        {{-- {{ config('app.name', 'Study App') }} --}}
                    </a>
                </div>
                
                @guest
                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary md:hidden">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-secondary md:hidden">Register</a>
                @endguest
                
                <div class="hidden space-x-4 md:flex" style="z-index: 1000;">
                    @auth
                        <x-button.nav href="{{ route('profile.exams') }}">Your Exams</x-button.nav>
                        <x-button.nav href="{{ route('exam.index') }}">Create/Manage Exams</x-button.nav>
                    @endauth
        
                    <x-button.nav href="{{ route('exam.public') }}">Public Exams</x-button.nav>
                    <x-button.nav href="{{ config('academy.discord_url') }}">Discord & Help</x-button.nav>

                    @guest
                        <x-button.nav href="{{ route('login') }}">Login</x-button.nav>
                        <x-button.nav href="{{ route('register') }}">Register</x-button.nav>
                    @endguest

                    @auth
                        <div class="dropdown dropdown-end">
                            <button tabindex="0" class="rounded-full btn btn-secondary">{{ Auth::user()->name }}</button>
                            <ul tabindex="0" class="p-2 shadow dropdown-content menu bg-base-100 rounded-box w-52">

                                <li> <a href="{{ route('profile.index') }}">Profile Settings</a></li>
                                <li> <a href="{{ route('profile.view', auth()->user()) }}">Your Transcripts</a></li>
                                @if ( auth()->user()->isAdmin )
                                    <li> <a href="{{ route('admin.index') }}">Admin Control Panel</a></li>
                                @endif
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mt-8">
            @if ($errors->any())
                <x-card.main>
                    @foreach($errors->all() as $error)
                        <div role="alert" class="my-2 alert alert-error">
                            <i class="fa-solid fa-hexagon-exclamation"></i>
                            <span>Error! {{ $error }}</span>
                        </div>
                    @endforeach
                </x-card.main>
            @endif

            @if (session('error'))
                <x-card.main>
                    @if(is_string(session('error')))
                        <div role="alert" class="my-2 alert alert-error d-flex align-items-center">
                            <i class="fa-solid fa-hexagon-exclamation me-2"></i>
                            <div>
                                <strong class="font-bold me-2">Error: </strong>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                </x-card.main>
            @endif

            @if (session('warning'))
                <x-card.main>
                    @if(is_string(session('warning')))
                        <div role="alert" class="my-2 alert alert-warning d-flex align-items-center">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <div>
                                <strong class="font-bold me-2">Warning: </strong>
                                <span>{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif
                </x-card.main>
            @endif

            @if (session('success'))
                <x-card.main>
                    @if(is_string(session('success')))
                        <div role="alert" class="my-2 alert alert-success d-flex align-items-center">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <div>
                                <strong class="font-bold me-2">Success!</strong>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif
                </x-card.main>
            @endif
            
            
            @yield('content')
        </main>
    </div>

    <footer class="p-10 mt-10 footer bg-neutral text-neutral-content">
        <nav>
            <h6 class="footer-title">Fun Stuff</h6>
            <a class="link link-hover" href="{{ route('home') }}">Welcome</a>
            <a class="link link-hover" href="{{ config('academy.discord_url') }}">Discord & Help</a>
        </nav>
        <nav>
            <h6 class="footer-title">Legal & Boring Stuff</h6>
            <a class="link link-hover" href="{{ route('privacy-policy') }}">Privacy Policy</a>
            <a class="link link-hover" href="{{ route('terms-of-service') }}">Terms of Service</a>
        </nav>
    </footer>

    <script>
        // function changeTheme(theme) {
        //     document.documentElement.setAttribute('data-theme', theme);
        // }
        document.getElementById('menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    
    @include('sweetalert::alert')
</body>
</html>
