const map = document.querySelector("#map-container");

var mymap = L.map("map").setView([0, 0], 13);
var latitudeInput = document.getElementById("latitude");
var longitudeInput = document.getElementById("longitude");

var marker;
var latlng;

if (latitudeInput && longitudeInput) {
    console.log(latitudeInput.value);
    console.log(longitudeInput.value);
    latlng = L.latLng(latitudeInput.value, longitudeInput.value);

    mymap.setView(latlng, 13);
    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(mymap);
    }
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
