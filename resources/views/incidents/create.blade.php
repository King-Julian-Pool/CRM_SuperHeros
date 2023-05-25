<x-app-layout>
    <script defer src="{{ asset('js/incidents/create.js') }}"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Incident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('incidents.store') }}">
                        @csrf
                        <div>
                            <input type="hidden" name="commune_id" id="commune_id"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ Auth::user()->id }}"
                                required />
                            @error('commune_id')
                                <span class="text-red-600 text-sm mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <x-input-label for="incident_type_id" class="block font-medium text-sm text-gray-700">
                                Type
                                d'incident
                            </x-input-label>
                            <select name="incident_type_id" id="incident_type_id"
                                class="form-select rounded-md shadow-sm mt-1 block w-full" required autofocus>
                                <option value="" disabled selected>Selectionner un type d'incident
                                </option>
                                @foreach ($incidentTypes as $incidentType)
                                    <option value="{{ $incidentType->id }}">{{ $incidentType->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('incident_type_id')" class="mt-2" />
                        </div>

                        {{-- carte --}}
                        <div class="mt-4">
                            <x-input-label :value="__('Localisation')" />
                            <div id="map" style="height: 400px; width: 100%"></div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>

                        {{-- <div class="mt-4">
                            <x-input-label for="latitude" class="block font-medium text-sm text-gray-700">
                                Latitude
                            </x-input-label>
                            <x-text-input type="text" name="latitude" id="latitude"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('latitude') }}"
                                required />
                            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="longitude" class="block font-medium text-sm text-gray-700">
                                Longitude
                            </x-input-label>
                            <x-text-input type="text" name="longitude" id="longitude"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ old('longitude') }}"
                                required />
                            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                        </div> --}}

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Déclarer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
