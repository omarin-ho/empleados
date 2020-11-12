<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <div style="width: 2rem; align-content: center" align="center">
            <x-jet-authentication-card-logo />
            </div>
        </x-slot>

        <div align="center" class="mb-4 text-sm text-gray-600">
            {{ __('Inserta tu correo') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div align="center">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div align="center">
                <x-jet-button>
                    {{ __('Clic aqui') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
