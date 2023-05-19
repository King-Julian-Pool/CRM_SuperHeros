window.addEventListener("DOMContentLoaded", (event) => {
    map.style.display = "none";
});

const roleRadios = document.querySelectorAll('input[name="role"]');
const heroContainer = document.querySelector("#hero-container");
const map = document.querySelector("#map-container");

roleRadios.forEach((radio) => {
    radio.addEventListener("change", (event) => {
        if (event.target.value === "hero") {
            heroContainer.style.display = "block";
            map.style.display = "inline";
        } else {
            heroContainer.style.display = "none";
            map.style.display = "none";
        }
    });
});

var mymap = L.map("map").setView([0, 0], 13);
var latitudeInput = document.getElementById("latitude");
var longitudeInput = document.getElementById("longitude");
var marker;
var latlng;

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
    var lat = 49.3823959;
    var lng = 1.0751564;
    latlng = L.latLng(lat, lng);
    latitudeInput.value = lat;
    longitudeInput.value = lng;
}

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

function onMapClick(e) {
    if (marker) {
        marker.setLatLng(e.latlng);
    } else {
        marker = L.marker(e.latlng).addTo(mymap);
    }
    latitudeInput.value = e.latlng.lat;
    longitudeInput.value = e.latlng.lng;
}

mymap.on("click", onMapClick);
