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
            <div class="container flex items-center justify-between px-4 py-4 mx-auto">
                <a href="{{ url('/') }}" class="text-lg font-semibold">{{ config('app.name', 'Study App') }}</a>
                <div class="hidden space-x-4 md:flex">
                    <a href="{{ route('tests') }}" class="btn btn-ghost">Exam List</a>
                    <a href="#" class="btn btn-ghost">Features</a>
                    <a href="#" class="btn btn-ghost">Pricing</a>
                    <a href="#" class="btn btn-ghost">Contact</a>
                    <div class="dropdown dropdown-end">
                        <button tabindex="0" class="btn btn-ghost rounded-btn">Theme</button>
                        {{-- <ul tabindex="0" class="p-2 shadow dropdown-content menu bg-base-100 rounded-box w-52">
                            <li><a href="#" onclick="changeTheme('light')">Light</a></li>
                            <li><a href="#" onclick="changeTheme('dark')">Dark</a></li>
                            <!-- Add more themes as needed -->
                        </ul> --}}
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="menu-button" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="hidden md:hidden">
                <a href="{{ route('tests') }}" class="block px-4 py-2">Exam List</a>
                <a href="#" class="block px-4 py-2">Features</a>
                <a href="#" class="block px-4 py-2">Pricing</a>
                <a href="#" class="block px-4 py-2">Contact</a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mt-8">
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
