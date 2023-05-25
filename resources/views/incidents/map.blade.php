<x-app-layout>
    <script defer src="{{ asset('js/incidents/map.js') }}"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Incidents') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div> --}}

    <label for="latitude" hidden>Latitude:</label>
    <input type="text" name="latitude" id="latitude" hidden>

    <label for="longitude" hidden>Longitude:</label>
    <input type="text" name="longitude" id="longitude" hidden>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class=" overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="map" data-incidents="{{ $incidents }}" data-types="{{ $incidentTypes }}"
                        style="width: 100%; height: 45em;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
