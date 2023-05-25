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
    const incidentData = JSON.parse(
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
                const incidentData = JSON.parse(
                    row.getAttribute("data-incident")
                );
                incidentType.textContent =
                    "Type: " + incidentData.incident_type.libelle;
                latitude.textContent = incidentData.latitude;
                longitude.textContent = incidentData.longitude;
                openModal();
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
    displayIncidentsAndHeroes(map, incidentData, heroesData);

    filterAndDisplayHeroes(incidentData, heroesData);
});
//#endregion

//#region fonctions
function openModal() {
    incidentModal.classList.remove("hidden");
    modalOverlay.classList.remove("hidden");
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
    // Créer un marker pour l'incident
    incidentMarker = L.marker([
        incidentData.latitude,
        incidentData.longitude,
    ]).addTo(map);

    // Draw a circle with a radius of 50 kilometers around the incident marker
    L.circle([incidentData.latitude, incidentData.longitude], {
        radius: 50000, // Radius in meters (50 kilometers = 50000 meters)
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

    // <span class="material-symbols-outlined">domino_mask</span>;

    // Créer un marker pour chaque hero
    heroesData.forEach((hero) => {
        L.marker(L.latLng(hero.latitude, hero.longitude), {
            icon: heroIcon,
        })
            .addTo(map)
            .bindPopup(`<b>${hero.name}</b><br>Téléphone: ${hero.phone}`);
    });
}

function filterAndDisplayHeroes(incidentData, heroesData) {
    // Clear existing heroes from the table
    const heroesTableBody = document.getElementById("heroesTableBody");
    heroesTableBody.innerHTML = "";

    // Iterate over the heroes and calculate their distance to the incident
    heroesData.forEach(function (hero) {
        const distance = calculateDistance(
            incidentData.latitude,
            incidentData.longitude,
            hero.latitude,
            hero.longitude
        );
        console.log(incidentData);

        // Display the hero in the table only if within 50 km of the incident
        if (distance <= 50) {
            const heroRow = document.createElement("tr");
            const heroNameCell = document.createElement("td");
            const heroPhoneCell = document.createElement("td");

            heroNameCell.textContent = hero.name;
            heroPhoneCell.textContent = hero.phone;

            heroRow.appendChild(heroNameCell);
            heroRow.appendChild(heroPhoneCell);
            heroesTableBody.appendChild(heroRow);
        }
    });
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Radius of the earth in kilometers
    const dLat = deg2rad(lat2 - lat1);
    const dLon = deg2rad(lon2 - lon1);
    const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(deg2rad(lat1)) *
            Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = R * c; // Distance in kilometers
    return distance;
}

function deg2rad(deg) {
    return deg * (Math.PI / 180);
}
//#endregion
