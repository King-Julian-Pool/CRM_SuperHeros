//#region événements
document.addEventListener("DOMContentLoaded", function () {
    //#region déclarations
    const incidentRows = document.querySelectorAll("tr[data-incident]");
    const incidentModal = document.getElementById("incidentModal");
    const modalOverlay = document.getElementById("modalOverlay");
    const incidentType = document.getElementById("incidentType");
    const latitude = document.getElementById("latitude");
    const longitude = document.getElementById("longitude");
    const closeModalBtn = document.getElementById("closeModal");
    const map = L.map("map").setView([0, 0], 13);
    var incidentData = JSON.parse(
        document.querySelector("[data-incident]").dataset.incident
    );
    const heroesData = JSON.parse(
        document.querySelector("[data-heroes]").dataset.heroes
    );
    var incidentMarker;
    //#endregion

    incidentModal.classList.add("hidden");

    incidentRows.forEach((row) => {
        row.addEventListener("click", function () {
            if (event.target.classList.contains("detail-incident")) {
                incidentData = JSON.parse(row.getAttribute("data-incident"));
                incidentType.textContent =
                    "Type: " + incidentData.incident_type.libelle;
                latitude.textContent = incidentData.latitude;
                longitude.textContent = incidentData.longitude;
                openModal(
                    map,
                    incidentData,
                    filterHeroes(incidentData, heroesData)
                );
            }
        });
    });

    closeModalBtn.addEventListener("click", function () {
        closeModal();
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-incident")) {
            event.preventDefault();
            deleteIncident(event.target);
        }
    });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("resolve-incident")) {
            event.preventDefault();
            resolveIncident(event.target);
        }
    });

    initMap(map, incidentData);
});
//#endregion

//#region fonctions
function openModal(map, incidentData, heroesData) {
    incidentModal.classList.remove("hidden");
    modalOverlay.classList.remove("hidden");

    displayIncidentsAndHeroes(map, incidentData, heroesData);

    displayHeroes(incidentData, heroesData);
}

function closeModal() {
    incidentModal.classList.add("hidden");
    modalOverlay.classList.add("hidden");
}

// Supression d'un incident
function deleteIncident(deleteButton) {
    var incidentId = deleteButton.getAttribute("data-incident-id");

    // Envoi d'une requête AJAX au serveur pour supprimer l'incident
    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch("/incidents/" + incidentId, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
    })
        .then(function (response) {
            // Gérer la réponse et effectuer les actions nécessaires
            if (response.ok) {
                var incidentRow = deleteButton.closest("tr");
                incidentRow.remove();
            } else {
                console.error("Incident deletion failed.");
            }
        })
        .catch(function (error) {
            console.error("Error deleting incident:", error);
        });
}

// Résolution d'un incident
function resolveIncident(resolveButton) {
    var incidentId = resolveButton.getAttribute("data-incident-id");

    // Envoi d'une requête AJAX au serveur pour marquer l'incident comme résolu

    var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch("/incidents/" + incidentId + "/resolve", {
        method: "PATCH",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            "Content-Type": "application/json",
        },
    })
        .then(function (response) {
            // Gérer la réponse et effectuer les actions nécessaires
            if (response.ok) {
                var incidentRow = resolveButton.closest("tr");
                incidentRow.remove();
            } else {
                console.error("Incident resolution failed.");
            }
        })
        .catch(function (error) {
            console.error("Error resolving incident:", error);
        });
}

function initMap(map, incidentData) {
    // Initiliaser la carte
    map.setView([incidentData.latitude, incidentData.longitude], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        maxZoom: 18,
        tileSize: 512,
        zoomOffset: -1,
    }).addTo(map);
}

function displayIncidentsAndHeroes(map, incidentData, heroesData) {
    // Clear existing markers from the map
    map.eachLayer(function (layer) {
        if (layer instanceof L.Marker || layer instanceof L.Circle) {
            map.removeLayer(layer);
        }
    });

    // Créer un marker pour l'incident
    incidentMarker = L.marker([
        incidentData.latitude,
        incidentData.longitude,
    ]).addTo(map);

    map.setView([incidentData.latitude, incidentData.longitude], 9);

    // Dessiner un cercle avec un rayon de 50 kilomètres autour du marker de l'incident
    L.circle([incidentData.latitude, incidentData.longitude], {
        radius: 50000,
        color: "blue",
        fillColor: "lightblue",
        fillOpacity: 0.4,
    }).addTo(map);

    heroIcon = L.divIcon({
        className: "custom-div-icon",
        html: "<div class='marker-pin'></div><i class='material-icons'></i>",
        iconSize: [30, 42],
        iconAnchor: [15, 42],
    });

    // Créer un marker pour chaque heros
    heroesData.forEach((hero) => {
        L.marker(L.latLng(hero.latitude, hero.longitude), {
            icon: heroIcon,
        })
            .addTo(map)
            .bindPopup(`<b>${hero.name}</b><br>Téléphone: ${hero.phone}`);
    });
}

function filterHeroes(incidentData, heroesData) {
    const filteredHeroes = [];

    // Boucler sur les héros et calculer leur distance par rapport à l'incident
    heroesData.forEach(function (hero) {
        const distance = calculateDistance(
            incidentData.latitude,
            incidentData.longitude,
            hero.latitude,
            hero.longitude
        );

        console.log(incidentData.incident_type_id);

        // Check if any of the hero's incident_types have an id equal to incidentData.incident_type_id
        var hasMatchingIncidentType = hero.incident_types.some(function (
            incidentType
        ) {
            return incidentType.id === incidentData.incident_type_id;
        });

        // Ajouter le hero au tableau filteredHeroes s'il est à moins de 50 km de l'incident
        if (distance <= 50 && hasMatchingIncidentType) {
            filteredHeroes.push(hero);
        }
    });

    return filteredHeroes;
}

function displayHeroes(incidentData, heroesData) {
    // Vider le tableau des héros
    const heroesTableBody = document.getElementById("heroesTableBody");
    heroesTableBody.innerHTML = "";

    heroesData.forEach(function (hero) {
        const heroRow = document.createElement("tr");
        const heroNameCell = document.createElement("td");
        const heroPhoneCell = document.createElement("td");

        heroNameCell.textContent = hero.name;
        heroPhoneCell.textContent = hero.phone;

        heroNameCell.classList.add("px-4", "py-2", "bg-gray-100", "text-left");
        heroPhoneCell.classList.add("px-4", "py-2", "bg-gray-100", "text-left");

        heroRow.appendChild(heroNameCell);
        heroRow.appendChild(heroPhoneCell);
        heroesTableBody.appendChild(heroRow);
    });
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Rayon de la terre en kilomètres
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat1)) *
            Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distance en kilomètres
    return distance;
}

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}
//#endregion
