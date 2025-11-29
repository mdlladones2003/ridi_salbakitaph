<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('reportForm', () => ({
        latitude: '',
        longitude: '',
        map: null,
        marker: null,
        mapInitialized: false,
        locationButtonText: 'Use My Current Location',

        init() {
            console.log('✅ Report form initialized');
            // Do not auto-init map; wait for user action
        },

        initMap() {
            if (this.mapInitialized) return;
            console.log('Initializing map...');

            this.$nextTick(() => {
                const mapEl = document.getElementById('map');
                if (!mapEl) {
                    console.error('❌ Map container not found');
                    return;
                }

                const defaultLat = 13.6218;
                const defaultLon = 123.1948;

                try {
                    // Initialize Leaflet map
                    this.map = L.map('map', {
                        center: [defaultLat, defaultLon],
                        zoom: 12,
                        zoomControl: true
                    });

                    // Add tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19
                    }).addTo(this.map);

                    // Add draggable marker
                    this.marker = L.marker([defaultLat, defaultLon], {
                        draggable: true,
                        autoPan: true,
                        title: 'Drag me to set location'
                    }).addTo(this.map);

                    // Set initial coordinates (and hidden inputs)
                    this.updateCoordinates(defaultLat, defaultLon);

                    // Handle marker drag
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.updateCoordinates(pos.lat, pos.lng);
                        this.updatePopup(pos.lat, pos.lng);
                    });

                    // Handle map clicks
                    this.map.on('click', (e) => {
                        const { lat, lng } = e.latlng;
                        this.updateMap(lat, lng);
                    });

                    // Initial popup
                    this.updatePopup(defaultLat, defaultLon);
                    this.marker.openPopup();

                    setTimeout(() => this.map.invalidateSize(), 150);

                    this.mapInitialized = true;
                    console.log('🗺️ Map initialized successfully');
                } catch (err) {
                    console.error('Error initializing map:', err);
                    alert('Failed to load map. Please refresh and try again.');
                }
            });
        },

        updateMap(lat, lon) {
            if (!this.map) return;
            const coords = [parseFloat(lat), parseFloat(lon)];

            this.map.setView(coords, 15, { animate: true });
            this.marker.setLatLng(coords);
            this.updateCoordinates(lat, lon);
            this.updatePopup(lat, lon);
        },

        updateCoordinates(lat, lon) {
            this.latitude = parseFloat(lat).toFixed(6);
            this.longitude = parseFloat(lon).toFixed(6);

            // Sync to hidden form fields
            const latInput = document.querySelector('input[name="latitude"]');
            const lonInput = document.querySelector('input[name="longitude"]');

            if (latInput) latInput.value = this.latitude;
            if (lonInput) lonInput.value = this.longitude;

            console.log(`📍 Coordinates updated: ${this.latitude}, ${this.longitude}`);
        },

        updatePopup(lat, lon) {
            if (!this.marker) return;
            this.marker.bindPopup(`
                <div style="text-align:center;">
                    <strong style="color:#1e40af;">Incident Location</strong><br>
                    <em style="color:#059669; font-size:11px;">Lat: ${parseFloat(lat).toFixed(6)}, Lng: ${parseFloat(lon).toFixed(6)}</em><br>
                    <small>Drag marker or click map to adjust</small>
                </div>
            `).openPopup();
        },

        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 5) {
                alert('⚠️ Maximum 5 images allowed.');
                e.target.value = '';
            }
        },

        showNotification(msg) {
            alert(msg);
        },

        async getCurrentLocation() {
            if (!navigator.geolocation) {
                this.showNotification('❌ Geolocation not supported.');
                return;
            }

            if (!this.mapInitialized) {
                this.initMap();
                await new Promise(r => setTimeout(r, 500));
            }

            this.locationButtonText = 'Detecting...';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const { latitude, longitude } = pos.coords;
                    console.log('📡 Current location:', latitude, longitude);
                    this.updateMap(latitude, longitude);
                    this.showNotification('✅ Location detected successfully!');
                    this.locationButtonText = 'Use My Current Location';
                },
                (err) => {
                    console.error('❌ Geolocation error:', err);
                    this.showNotification('Could not detect your location. Please set it manually.');
                    this.locationButtonText = 'Use My Current Location';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }
    }));
});
</script>
