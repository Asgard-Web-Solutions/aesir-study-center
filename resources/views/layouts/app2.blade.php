<!DOCTYPE html>
<html lang="en" data-theme="night">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Study App') }}</title>
    <script src="https://kit.fontawesome.com/ac81ff684d.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
</head>
<body class="text-gray-900 base-100">
    <div class="container mx-auto">
        <!-- Navbar -->
        <nav class="rounded-b-lg shadow-md navbar bg-neutral text-neutral-content">
            <div class="dropdown md:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
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
                </div>
                <ul
                  tabindex="0"
                  class="menu menu-md dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                    @auth
                        <x-button.mobile href="{{ route('profile.exams') }}">Your Exams</x-button.mobile>
                        <x-button.mobile href="{{ route('profile.myexams') }}">Create/Manage Exams</x-button.mobile>
                    @endauth
                    <x-button.mobile href="{{ route('public-exams') }}">Public Exams</x-button.mobile>
                    <x-button.mobile href="https://community.jonzenor.com/viewforum.php?f=31">Forums & Help</x-button.mobile>
                    
                    @auth
                        @if ( auth()->user()->hasRole('admin') )
                            {{-- <x-button.mobile href="{{ route('manage-exams') }}">Manage Exams</x-button.mobile> --}}
                        @endif
                    @endauth

                    <hr class="my-2" />
                    @auth
                        <x-button.mobile href="{{ route('home') }}">Home</x-button.mobile>

                        <a href="{{ route('logout') }}" class="block px-4 py-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                    @endauth
                </ul>
              </div>

            <div class="container flex items-center justify-between px-4 py-4 mx-auto">
                <a href="{{ url('/') }}" class="text-lg font-semibold">{{ config('app.name', 'Study App') }}</a>
                <div class="hidden space-x-4 md:flex">
                    @auth
                        <x-button.nav href="{{ route('profile.exams') }}">Your Exams</x-button.nav>
                        <x-button.nav href="{{ route('profile.myexams') }}">Create/Manage Exams</x-button.nav>
                    @endauth
        
                    <x-button.nav href="{{ route('public-exams') }}">Public Exams</x-button.nav>
                    <x-button.nav href="https://community.jonzenor.com/viewforum.php?f=31">Forums & Help</x-button.nav>

                    @auth
                        @if ( auth()->user()->hasRole('admin') )
                            {{-- <x-button.nav href="{{ route('manage-exams') }}">Manage Exams</x-button.nav> --}}
                        @endif
                    @endauth

                    {{-- <a href="#" class="btn btn-ghost">Features</a>
                    <a href="#" class="btn btn-ghost">Pricing</a>
                    <a href="#" class="btn btn-ghost">Contact</a> --}}
                    @auth
                        <div class="dropdown dropdown-end">
                            <button tabindex="0" class="rounded-full btn btn-secondary">{{ Auth::user()->name }}</button>
                            <ul tabindex="0" class="p-2 shadow dropdown-content menu bg-base-100 rounded-box w-52">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    {{ csrf_field() }}
                                    </form>
                                </li>
                                <!-- Add more themes as needed -->
                            </ul>
                        </div>
                    @endauth
                    @guest
                        
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mt-8">
            @if ($errors->any())
                <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                    <strong class="font-bold">Whoops! Something went wrong.</strong>
                    <ul class="mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('alert'))
                @if(is_string(session('alert')))
                    <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <p class="mt-1">{{ session('alert') }}</p>
                    </div>
                @endif
            @endif
            
            <!-- Section to show session error messages -->
            @if (session('error'))
                @if(is_string(session('error')))
                    <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <p class="mt-1">{{ session('error') }}</p>
                    </div>
                @endif
            @endif
            
            <!-- Section to show session warning messages -->
            @if (session('warning'))
                @if(is_string(session('warning')))
                    <div class="relative px-4 py-3 mb-4 text-yellow-700 bg-yellow-100 border border-yellow-400 rounded" role="alert">
                        <strong class="font-bold">Warning!</strong>
                        <p class="mt-1">{{ session('warning') }}</p>
                    </div>
                @endif
            @endif
        
            
            @yield('content')
        </main>
    </div>

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
