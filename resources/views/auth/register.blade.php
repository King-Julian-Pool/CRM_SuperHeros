<x-guest-layout>
    <script defer src="{{ asset('js/auth/register.js') }}"></script>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Commune/Hero Radio Buttons -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('S\'enregistrer en tant que')" />
            <div class="mt-2">
                <div>
                    <x-input-label for="commune" class="inline-flex items-center">
                        <input type="radio" name="role" id="commune" class="form-radio" value="commune"
                            required />
                        <span class="ml-2">{{ __('Commune') }}</span>
                    </x-input-label>
                </div>
                <div class="mt-2">
                    <x-input-label for="hero" class="inline-flex items-center">
                        <input type="radio" name="role" id="hero" class="form-radio" value="hero"
                            required />
                        <span class="ml-2">{{ __('Hero') }}</span>
                    </x-input-label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Incident Types -->
        <div id="hero-container" class="mt-4" style="display:none;">
            <x-input-label :value="__('Types d\'incident')" />
            @foreach ($incidentTypes as $incidentType)
                <div class="mt-2">
                    <x-input-label for="{{ $incidentType->name }}" class="inline-flex items-center">
                        <input type="checkbox" name="incident_types[]" id="{{ $incidentType->id }}"
                            value="{{ $incidentType->id }}" class="form-checkbox">
                        <span class="ml-2">{{ $incidentType->libelle }}</span>
                    </x-input-label>
                </div>
            @endforeach
            <x-input-error :messages="$errors->get('incident_types')" class="mt-2" />


            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
        </div>

        <div id="map-container">
            <div id="map" style="height: 400px;"></div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('S\'enregistrer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
