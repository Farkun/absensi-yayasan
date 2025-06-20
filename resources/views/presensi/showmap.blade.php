<style>
    #map {
        height: 250px;
    }
</style>
<div id="map"></div>

<script>
    var lokasi = "{{ $attendance->location_in }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];
    var map = L.map('map').setView([latitude, longitude], 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([-6.5570523, 106.7689701], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 200
    }).addTo(map);
    var popup = L.popup()
    .setLatLng([latitude, longitude])
    .setContent("{{ $attendance->nama_lengkap }}")
    .openOn(map);
</script>