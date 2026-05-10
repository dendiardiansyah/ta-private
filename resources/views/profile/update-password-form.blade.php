<x-common.form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-common.label for="current_password" value="{{ __('Current Password') }}" />
            <x-common.input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
            <x-common.input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-common.label for="password" value="{{ __('New Password') }}" />
            <x-common.input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" />
            <x-common.input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-common.label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <x-common.input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-common.input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-common.action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-common.action-message>

        <x-common.button>
            {{ __('Save') }}
        </x-common.button>
    </x-slot>
</x-common.form-section>
