<x-common.form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informasi Akun') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Anda bisa memperbarui informasi anda.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
            <!-- Profile Photo File Input -->
            <input type="file" id="photo" class="hidden"
                wire:model.live="photo"
                x-ref="photo"
                x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

            <x-common.label for="photo" value="{{ __('Photo') }}" />

            <!-- Current Profile Photo -->
            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full h-20 w-20 object-cover">
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" x-show="photoPreview" style="display: none;">
                <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                    x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <x-common.secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                {{ __('Select A New Photo') }}
            </x-common.secondary-button>

            @if ($this->user->profile_photo_path)
            <x-common.secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                {{ __('Remove Photo') }}
            </x-common.secondary-button>
            @endif

            <x-common.input-error for="photo" class="mt-2" />
        </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-common.label for="name" value="{{ __('Name') }}" />
            <x-common.input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-common.input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-common.label for="email" value="{{ __('Email') }}" />
            <x-common.input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-common.input-error for="email" class="mt-2" />

            <!-- Nomor Telepon -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-common.label for="nomor_telepon" value="{{ __('Nomor Telepon') }}" />
                <x-common.input id="nomor_telepon" type="text" class="mt-1 block w-full" wire:model="state.nomor_telepon" required />
                <x-common.input-error for="nomor_telepon" class="mt-2" />
            </div>

            <!-- Alamat Penjemputan -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <x-common.label for="alamat_penjemputan" value="{{ __('Alamat Penjemputan') }}" />
                <x-common.input id="alamat_penjemputan" type="text" class="mt-1 block w-full" wire:model="state.alamat_penjemputan" required />
                <x-common.input-error for="alamat_penjemputan" class="mt-2" />
            </div>

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
            <p class="text-sm mt-2">
                {{ __('Your email address is unverified.') }}

                <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                    {{ __('Click here to re-send the verification email.') }}
                </button>
            </p>

            @if ($this->verificationLinkSent)
            <p class="mt-2 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to your email address.') }}
            </p>
            @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-common.action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-common.action-message>

        <x-common.button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-common.button>
    </x-slot>
</x-common.form-section>