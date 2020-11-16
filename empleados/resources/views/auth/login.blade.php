<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <div style="width: 2rem; align-content: center" align="center">
                <x-jet-authentication-card-logo />
            </div>
        </x-slot>

        <x-jet-validation-errors class="" />

        @if (session('status'))
            <div class="">
                {{ session('status') }}
            </div>
        @endif

        <form class="container-fluid" method="POST" action="{{ route('login') }}">
            @csrf

            <div align="right" class="form-group">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div><p></p></div>

            <div align="right" class="form-group">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div align="right" class="form-group">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div align="right">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="btn btn-info">
                    {{ __('Login') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
