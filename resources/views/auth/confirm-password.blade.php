<x-guest-layout>
    <x-common.authentication-card>
        <x-slot name="logo">
            <x-common.authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-common.validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-common.label for="password" value="{{ __('Password') }}" />
                <x-common.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-common.button class="ms-4">
                    {{ __('Confirm') }}
                </x-common.button>
            </div>
        </form>
    </x-common.authentication-card>
</x-guest-layout>
