<x-guest-layout>
    <x-common.authentication-card>
        <x-slot name="logo">
            <x-common.authentication-card-logo />
        </x-slot>

        <x-common.validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-common.label for="email" value="{{ __('Email') }}" />
                <x-common.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-common.label for="password" value="{{ __('Password') }}" />
                <x-common.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-common.label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-common.input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-common.button>
                    {{ __('Reset Password') }}
                </x-common.button>
            </div>
        </form>
    </x-common.authentication-card>
</x-guest-layout>
