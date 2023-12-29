@extends('Pegawai.layouts.index')

@section('container')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Selamat Datang di Sistem Absensi</h6>
        </div>
        <div class="card-body">
            <p>Silakan gunakan tombol di bawah untuk memindai QR code atau menggunakan live location.</p>
            <div id="optionButtons">
                <button type="button" class="btn btn-primary" onclick="startScanner()">
                    Scan QR Code
                </button>
                <button type="button" class="btn btn-primary" onclick="startLiveLocation()">
                    Live Location
                </button>
            </div>
            <!-- Your content goes here -->
            <div id="qrcodeImage" style="display: none;"></div>
            <div id="map" style="display: none; height: 300px;"></div>
            <button type="button" class="btn btn-secondary" onclick="closeScannerOrMap()">
                Close
            </button>
        </div>
    </div>

    <script>
        var quaggaInitialized = false;
        var map;

        function startScanner() {
            // Hide the map and show the QR code image
            hideMap();
            document.getElementById('qrcodeImage').style.display = 'block';

            // Initialize Quagga scanner
            try {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#qrcodeImage'),
                        constraints: {
                            width: 480,
                            height: 320,
                        },
                    },
                    decoder: {
                        readers: ['code_128_reader']
                    }
                }, function(err) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    Quagga.canvas.ctx.willReadFrequently = true;
                    Quagga.start();
                });

                Quagga.onDetected(function(data) {
                    var qrCodeData = data.codeResult.code;
                    console.log('QR Code detected:', qrCodeData);
                    // Handle the detected QR code data as needed
                });
            } catch (error) {
                console.error('Error during Quagga initialization:', error);
            }
        }

        function startLiveLocation() {
            // Hide the QR code image and show the map
            hideScanner();
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
        }

        function getLocationAndDisplayOnMap(map) {
            // Get the user's location using the browser's geolocation API
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Update the map to show the user's location
                updateMapLocation(map, latitude, longitude);
            });
        }

        function updateMapLocation(map, latitude, longitude) {
            // Update the Leaflet map to show the user's location
            map.setView([latitude, longitude], 15); // Set the view to the user's location
            L.marker([latitude, longitude]).addTo(map); // Add a marker at the user's location
        }

        // Close scanner or map
        function closeScannerOrMap() {
            // Check if Quagga is initialized and running
            if (quaggaInitialized) {
                // Stop Quagga scanner if it's running
                Quagga.stop();
                // Reset the initialization flag for the next time the scanner is opened
                quaggaInitialized = false;
            }

            // Hide both QR code image and map
            hideScanner();
            hideMap();
            showOptionButtons();
        }

        // Hide scanner
        function hideScanner() {
            document.getElementById('qrcodeImage').style.display = 'none';
            try {
                // Try to access the camera stream and stop it explicitly
                var mediaStream = document.querySelector('#qrcodeImage video').srcObject;
                var tracks = mediaStream.getTracks();

                tracks.forEach(function(track) {
                    track.stop();
                });

                document.querySelector('#qrcodeImage video').srcObject = null;
            } catch (error) {
                // Ignore any errors
            }
        }

        // Hide map
        function hideMap() {
            if (map) {
                map.remove(); // Remove the existing map
                map = null; // Reset the map variable
            }
            document.getElementById('map').style.display = 'none';
        }

        // Show option buttons
        function showOptionButtons() {
            document.getElementById('optionButtons').style.display = 'block';
        }
    </script>
@endsection
