@extends('layouts.app2')

@section('content')

    <x-card.main title="Login">
        <x-card.mini>
            <form class="w-full p-6" method="POST" action="{{ route('login') }}">
                @csrf

                <x-form.text name="email" label="Email Address" />

                <x-form.text type="password" name="password" label="Password" />

                <div class="flex mb-6">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="ml-3 text-sm text-gray-700" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="flex flex-wrap items-center">
                    <button type="submit" class="px-4 py-2 font-bold text-gray-100 bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="ml-auto text-sm text-blue-500 no-underline whitespace-no-wrap hover:text-blue-700" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <p class="w-full mt-8 -mb-4 text-xs text-center text-gray-700">
                            {{ __("Don't have an account?") }}
                            <a class="text-blue-500 no-underline hover:text-blue-700" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        </p>
                    @endif
                </div>

            </form>
        </x-card.mini>
    </x-card.main>


    </div>
@endsection
