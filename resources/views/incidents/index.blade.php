<x-app-layout>
    <script defer src="{{ asset('js/incidents/index.js') }}"></script>
    <style defer>
        .marker-pin {
            width: 30px;
            height: 30px;
            border-radius: 50% 50% 50% 0;
            background: #c21a21;
            position: absolute;
            transform: rotate(-45deg);
            left: 50%;
            top: 50%;
            margin: -15px 0 0 -15px;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des incidents') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2">
                    <div class="table-responsive">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th
                                        class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Résolution
                                    </th>
                                    <th
                                        class="px-2 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Suppression
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incidents as $incident)
                                    <tr data-incident="{{ json_encode($incident) }}"
                                        data-heroes="{{ json_encode($heroes) }}">
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <button class="text-gray-300 text-left detail-incident">
                                                {{ $incident->incidentType->libelle }}
                                            </button>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <button class="text-green-600 text-left resolve-incident"
                                                data-incident-id="{{ $incident->id }}">
                                                Marquer comme résolu
                                            </button>
                                        </td>
                                        <td class="px-2 py-4 whitespace-nowrap">
                                            <button class="text-red-600 text-left delete-incident"
                                                data-incident-id="{{ $incident->id }}">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('incidents.detail-modal')
</x-app-layout>
