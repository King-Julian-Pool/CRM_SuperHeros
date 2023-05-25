//#region déclarations
var mymap = L.map("map").setView([0, 0], 13);
var latitudeInput = document.getElementById("latitude");
var longitudeInput = document.getElementById("longitude");
var marker;
var latlng;
//#endregion

//#region événements
window.addEventListener("DOMContentLoaded", (event) => {
    setLocation();
    initMap();
});
//#region

//#region fonctions
function setLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                latlng = L.latLng(
                    position.coords.latitude,
                    position.coords.longitude
                );

                mymap.setView(latlng, 13);
                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(mymap);
                }

                latitudeInput.value = position.coords.latitude;
                longitudeInput.value = position.coords.longitude;
            },
            function (error) {
                console.error(error);
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }

    if (latlng == null) {
        var lat = 48.8566806;
        var lng = 2.3298617;
        latlng = L.latLng(lat, lng);
        latitudeInput.value = lat;
        longitudeInput.value = lng;
    }
}

function initMap() {
    mymap.setView(latlng, 13);
    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(mymap);
    }

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        maxZoom: 18,
        tileSize: 512,
        zoomOffset: -1,
    }).addTo(mymap);

    mymap.on("click", onMapClick);
}

function onMapClick(e) {
    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(mymap);
    }
    latitudeInput.value = e.latlng.lat;
    longitudeInput.value = e.latlng.lng;
}
//#endregion
