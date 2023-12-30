<!-- resources/views/Pegawai/Absensi/index.blade.php -->

@extends('Pegawai.layouts.index')

@section('container')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white">
            <li class="breadcrumb-item"><a href="{{ url('/pegawai') }}">Pegawai</a></li>
            <li class="breadcrumb-item active" aria-current="page">Form Absensi</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        @if ($successMessage)
            <div class="alert alert-success" role="alert">
                {{ $successMessage }}
            </div>
        @endif
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Absensi</h6>
        </div>
        <div class="card-body">
            <form action="{{ url('/pegawai/absensi/store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="attendance_status">Status Absensi</label>
                    <select name="attendance_status" class="form-control" required>
                        <option value="">no choose</option>
                        <option value="hadir">Hadir</option>
                        <option value="alfa">Alfa</option>
                        <option value="sakit">Sakit</option>
                        <!-- Tambahkan opsi sesuai kebutuhan -->
                    </select>
                </div>
                <div class="form-group" id="inputAttendanceType" style="display: none">
                    <label for="attendance_type">Pilihan</label>
                    <select name="attendance_type" class="form-control" required>
                        <option value="onsite">Onsite</option>
                        <option value="online">Online</option>
                        <!-- Tambahkan opsi sesuai kebutuhan -->
                    </select>
                </div>


                <div class="form-group" id="fileInputSection" style="display: none;">
                    <label for="file">File</label>
                    <input type="file" name="file" class="form-control">
                </div>

                <div class="form-group" id="mapInputSection" style="display: none;">
                    <label for="longitude_attendences">Longitude</label>
                    <input type="text" name="longitude_attendences" id="longitude_attendences" class="form-control">

                    <label for="latitude_attendences">Latitude</label>
                    <input type="text" name="latitude_attendences" id="latitude_attendences" class="form-control mt-2">

                    <div id="map" style="height: 300px;" class="mt-3"></div>
                </div>

                <!-- Tambahkan input atau field lainnya sesuai kebutuhan -->

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- <script defer>
        var watchId;
        var attendanceTypeSelect = document.querySelector('select[name="attendance_type"]');
        var attendanceStatusSelect = document.querySelector('select[name="attendance_status"]');
        document.addEventListener('DOMContentLoaded', function() {
            // Menangani perubahan dalam dropdown

            // Menangani perubahan dalam dropdown 'attendance_type'
            attendanceTypeSelect.addEventListener('change', function() {
                toggleSections(attendanceTypeSelect.value, attendanceStatusSelect.value);
            });

            // Menangani perubahan dalam dropdown 'attendance_status'
            attendanceStatusSelect.addEventListener('change', function() {
                toggleSections(attendanceTypeSelect.value, attendanceStatusSelect.value);
            });
            // Membuat peta dengan Leaflet
            var map = L.map('map').setView([-6.2088, 106.8456], 13); // Ganti dengan koordinat default

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Menangani klik pada peta untuk mendapatkan koordinat
            map.on('click', function(e) {
                var latitude = e.latlng.lat;
                var longitude = e.latlng.lng;

                // Update nilai input longitude dan latitude
                document.getElementById('longitude_attendences').value = longitude;
                document.getElementById('latitude_attendences').value = latitude;
                // Update marker position
                if (liveLocationMarker) {
                    liveLocationMarker.setLatLng([latitude, longitude]).update();
                }
            });

            function getLocationAndDisplayOnMap(map) {
                // Get the user's location using the browser's geolocation API
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Update the map to show the user's location
                    updateMapLocation(map, latitude, longitude);

                    // Update nilai input longitude dan latitude
                    document.getElementById('longitude_attendences').value = longitude;
                    document.getElementById('latitude_attendences').value = latitude;
                });
            }

            function enableLiveLocation() {
                document.getElementById('map').style.display = 'block';

                if (map) {
                    map.remove(); // Remove the existing map
                }

                // Initialize Leaflet map
                map = L.map('map').setView([0, 0], 2); // Default view

                // Add a tile layer (you may need to replace this URL with your own tile layer)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Get live location and display on the map
                getLocationAndDisplayOnMap(map);

                // Hentikan perekaman jika sudah berjalan
                if (watchId) {
                    navigator.geolocation.clearWatch(watchId);
                }

                // Mulai perekaman posisi
                watchId = navigator.geolocation.watchPosition(
                    function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        // Update the map to show the user's live location
                        updateMapLocation(map, latitude, longitude);
                    },
                    function(error) {
                        console.error('Error getting live location:', error);
                    }
                );
            }


            function updateMapLocation(map, latitude, longitude) {
                // Check if map is defined
                if (!map) {
                    // If not, initialize the map
                    map = L.map('map').setView([latitude, longitude], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    // Reattach the event listener for map click
                    map.on('click', function(e) {
                        var latitude = e.latlng.lat;
                        var longitude = e.latlng.lng;

                        // Update nilai input longitude dan latitude
                        document.getElementById('longitude_attendences').value = longitude;
                        document.getElementById('latitude_attendences').value = latitude;
                        // Update marker position
                        if (liveLocationMarker) {
                            liveLocationMarker.setLatLng([latitude, longitude]).update();
                        }
                    });
                } else {
                    // Update the Leaflet map to show the user's live location
                    map.setView([latitude, longitude], 15); // Set the view to the user's live location
                }

                // Tambahkan atau perbarui marker live location
                if (typeof liveLocationMarker === 'undefined') {
                    // Buat marker jika belum ada
                    liveLocationMarker = L.marker([latitude, longitude], {
                        draggable: true
                    }).addTo(map);

                    liveLocationMarker.on('dragend', function(e) {
                        var newLatitude = e.target.getLatLng().lat;
                        var newLongitude = e.target.getLatLng().lng;

                        // Update nilai input longitude dan latitude
                        document.getElementById('longitude_attendences').value = newLongitude;
                        document.getElementById('latitude_attendences').value = newLatitude;

                        enableLiveLocation();
                    });
                } else {
                    // Perbarui posisi marker jika sudah ada
                    liveLocationMarker.setLatLng([latitude, longitude]).update();
                }

                // Set marker agar terlihat
                liveLocationMarker.bindPopup("You are here!").openPopup();
            }

            function updateMapSize() {
                if (map) {
                    map.invalidateSize();
                }
            }

            function toggleSections(selectedType, selectedStatus) {
                var fileInputSection = document.getElementById('fileInputSection');
                var mapInputSection = document.getElementById('mapInputSection');

                fileInputSection.style.display = 'none';
                mapInputSection.style.display = 'none';

                if (selectedStatus === 'hadir') {
                    if (selectedType === 'onsite') {
                        // Menunjukkan bagian peta untuk onsite
                        mapInputSection.style.display = 'none';
                    } else if (selectedType === 'online') {
                        // Menunjukkan bagian file untuk online
                        fileInputSection.style.display = 'block';
                        mapInputSection.style.display = 'block';
                    }
                } else if (selectedStatus === 'sakit' || selectedStatus === 'alfa') {
                    // Untuk 'sakit' atau 'alfa', hanya menunjukkan bagian file
                    fileInputSection.style.display = 'block';
                }

                // Perbarui ukuran peta setelah menunjukkan/menyembunyikan
                setTimeout(function() {
                    updateMapSize();
                }, 1000);
                if (selectedStatus === 'hadir' || selectedType === 'online') {
                    enableLiveLocation();
                }
            }
        });
    </script> --}}
    <script defer>
        var watchId;
        var map;
        var liveLocationMarker;
        var attendanceTypeSelect = document.querySelector('select[name="attendance_type"]');
        var attendanceStatusSelect = document.querySelector('select[name="attendance_status"]');

        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');

            form.addEventListener('submit', function(event) {
                var statusSelect = document.querySelector('select[name="attendance_status"]');

                if (statusSelect.value === "") {
                    alert("Silakan pilih status absensi");
                    event.preventDefault(); // Mencegah pengiriman formulir jika validasi gagal
                }
            });
            // Menangani perubahan dalam dropdown
            attendanceTypeSelect.addEventListener('change', function() {
                toggleSections(attendanceTypeSelect.value, attendanceStatusSelect.value);
            });

            attendanceStatusSelect.addEventListener('change', function() {
                toggleSections(attendanceTypeSelect.value, attendanceStatusSelect.value);
            });

            // Other initialization code...

            // Initialize Leaflet map
            map = L.map('map').setView([0, 0], 2); // Default view
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Get live location and display on the map
            enableLiveLocation();

            // Initial live location setup
            getLocationAndDisplayOnMap(map);
        });

        function getLocationAndDisplayOnMap(map) {
            // Get the user's location using the browser's geolocation API
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Update the map to show the user's location
                updateMapLocation(map, latitude, longitude);

                // Update nilai input longitude dan latitude
                document.getElementById('longitude_attendences').value = longitude;
                document.getElementById('latitude_attendences').value = latitude;
            });
        }

        function enableLiveLocation() {
            document.getElementById('map').style.display = 'block';

            // Hentikan perekaman jika sudah berjalan
            if (watchId) {
                navigator.geolocation.clearWatch(watchId);
            }

            // Mulai perekaman posisi
            watchId = navigator.geolocation.watchPosition(
                function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Update the map to show the user's live location
                    updateMapLocation(map, latitude, longitude);
                },
                function(error) {
                    console.error('Error getting live location:', error);
                }
            );
        }

        function updateMapLocation(map, latitude, longitude) {
            // Update the Leaflet map to show the user's live location
            map.setView([latitude, longitude], 15); // Set the view to the user's live location

            // Tambahkan atau perbarui marker live location
            if (typeof liveLocationMarker === 'undefined') {
                // Buat marker jika belum ada
                liveLocationMarker = L.marker([latitude, longitude], {
                    draggable: true
                }).addTo(map);

                liveLocationMarker.on('dragend', function(e) {
                    var newLatitude = e.target.getLatLng().lat;
                    var newLongitude = e.target.getLatLng().lng;

                    // Update nilai input longitude dan latitude
                    document.getElementById('longitude_attendences').value = newLongitude;
                    document.getElementById('latitude_attendences').value = newLatitude;
                });
            } else {
                // Perbarui posisi marker jika sudah ada
                liveLocationMarker.setLatLng([latitude, longitude]).update();
            }

            // Set marker agar terlihat
            liveLocationMarker.bindPopup("You are here!").openPopup();
        }

        function updateMapSize() {
            if (map) {
                map.invalidateSize();
            }
        }

        function toggleSections(selectedType, selectedStatus) {
            var fileInputSection = document.getElementById('fileInputSection');
            var mapInputSection = document.getElementById('mapInputSection');
            var attendanceTypeInputSection = document.getElementById('inputAttendanceType');

            fileInputSection.style.display = 'none';
            mapInputSection.style.display = 'none';
            attendanceTypeInputSection.style.display = 'none';

            if (selectedStatus === 'hadir') {
                attendanceTypeInputSection.style.display = 'block';
                if (selectedType === 'onsite') {
                    // Menunjukkan bagian peta untuk onsite
                    mapInputSection.style.display = 'none';
                } else if (selectedType === 'online') {
                    // Menunjukkan bagian file untuk online
                    fileInputSection.style.display = 'block';
                    mapInputSection.style.display = 'block';
                }
            } else if (selectedStatus === 'sakit') {
                // Untuk 'sakit' atau 'alfa', hanya menunjukkan bagian file
                fileInputSection.style.display = 'block';
            } else if (selectedStatus === 'alfa') {
                fileInputSection.style.display = 'none';
                mapInputSection.style.display = 'none';
            }

            // Perbarui ukuran peta setelah menunjukkan/menyembunyikan
            setTimeout(function() {
                updateMapSize();
            }, 1000);

            if (selectedStatus === 'hadir' || selectedType === 'online') {
                enableLiveLocation();
            }
        }
    </script>
@endsection
