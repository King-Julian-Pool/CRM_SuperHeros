<script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Incident Detail Modal -->
<div class="absolute inset-0 bg-gray-500 opacity-75 hidden" id="modalOverlay"></div>
<div id="incidentModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-gray-100 rounded-lg p-6 w-3/4">
        <h3 class="font-semibold text-lg mb-4" id="incidentTitle"></h3>
        <p class="text-gray-600" id="incidentDescription"></p>
        <p class="mt-4" id="incidentType"></p>
        <p id="location">Localisation :</p>
        <div class="flex">
            <p style='margin-left: 10px;'>Latitude : </p>
            <p id="latitude"style='margin-left: 10px;'></p>
        </div>
        <div class="flex">
            <p style='margin-left: 10px;'> Longitude : </p>
            <p id="longitude" style='margin-left: 10px;'></p>
        </div>

        <div id="map" style="height: 300px; width: 100%;"></div>

        <table class="mt-4 w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-100 text-left">Héros</th>
                    <th class="px-4 py-2 bg-gray-100 text-left">Téléphone</th>
                </tr>
            </thead>
            {{-- <tbody>
                @foreach ($heroes as $hero)
                    <tr>
                        <td class="px-4 py-2">{{ $hero->name }}</td>
                        <td class="px-4 py-2">{{ $hero->phone }}</td>
                    </tr>
                @endforeach
            </tbody> --}}
            <tbody id="heroesTableBody">
            </tbody>
        </table>

        <button id="closeModal" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded-md">Close</button>
    </div>
</div>
