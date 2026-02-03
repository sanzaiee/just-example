@extends('backend.form.master')
@section('form-content')
    <div class="row g-3">
        @foreach ([['type', 'Save Address As'], ['name', 'Name'], ['email', 'Email'], ['phone', 'Phone']] as $item)
            @include('backend.form.collection', [
                'data' => [
                    'name' => $item[0],
                    'label' => $item[1],
                ],
                'required' => $item[0] === 'type',
                'model' => $model ?? null,
                'type' => 'text',
            ])
        @endforeach

        @include('backend.form.collection', [
            'data' => [
                'name' => 'house_no',
                'label' => 'Apt / Suite / Floor / Unit',
            ],
            'required' => false,
            'model' => $model ?? null,
            'type' => 'text',
        ])

        <div class="col-12">
            <label class="form-label">Address (search or click on map)</label>

            <div id="address-map"
                style="height: 320px; width: 100%; border-radius: 0.375rem; border: 1px solid var(--bs-border-color);"
                class="mb-2">
            </div>

            <input type="text" name="address" id="address" class="form-control"
                placeholder="Selected address (or type manually)" value="{{ old('address', $model->address ?? '') }}">
            @error('address')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        @foreach ([['city', 'City'], ['postal_code', 'Postal Code']] as $item)
            <div class="col-md-6">
                <label class="form-label" for="{{ $item[0] }}">{{ $item[1] }} *</label>
                <input type="text" name="{{ $item[0] }}" id="{{ $item[0] }}" class="form-control"
                    value="{{ old($item[0], $model?->{$item[0]} ?? '') }}">
                @error($item[0])
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        @endforeach

        <div class="col-md-6">
            <label class="form-label" for="latitude">Latitude</label>
            <input type="text" name="latitude" id="latitude" class="form-control"
                value="{{ old('latitude', $model->latitude ?? '') }}">
            @error('latitude')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label" for="longitude">Longitude</label>
            <input type="text" name="longitude" id="longitude" class="form-control"
                value="{{ old('longitude', $model->longitude ?? '') }}">
            @error('longitude')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

    </div>
@endsection

@push('css')
    {{-- Leaflet core styles --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    {{-- Optional: Leaflet Geocoder control styles (for search box on the map itself) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endpush

@push('custom-scripts')
    {{-- Leaflet core JS --}}
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    {{-- Leaflet geocoder plugin (used for search) --}}
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // -----------------------------
            // 1. Cache DOM elements
            // -----------------------------
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const addressInput = document.getElementById('address');
            const cityInput = document.getElementById('city');
            const postalInput = document.getElementById('postal_code');

            // -----------------------------
            // 2. Initial map setup
            // -----------------------------
            const existingLat = latInput?.value ? parseFloat(latInput.value) : NaN;
            const existingLng = lngInput?.value ? parseFloat(lngInput.value) : NaN;
            const hasExisting = !isNaN(existingLat) && !isNaN(existingLng);

            // Fallback when geolocation is denied or unavailable
            const fallbackLat = 43.6532;
            const fallbackLng = -79.3832;

            // -----------------------------
            // 3. Helper: update form inputs
            // -----------------------------
            function updateInputs(fullAddress, lat, lng) {
                if (addressInput) addressInput.value = fullAddress || '';
                if (latInput) latInput.value = lat != null ? lat : '';
                if (lngInput) lngInput.value = lng != null ? lng : '';

                if (!fullAddress) return;

                const parts = fullAddress.split(',');
                if (parts.length >= 3 && cityInput) cityInput.value = parts[parts.length - 3].trim();
                if (parts.length >= 1 && postalInput) postalInput.value = parts[parts.length - 1].trim();
            }

            // -----------------------------
            // 4. Initialize map and marker with given center
            // -----------------------------
            function initMapWithCenter(initialLatLng, initialZoom) {
                const map = L.map('address-map').setView(initialLatLng, initialZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // -----------------------------
                // 5. Shared reverse geocode function
                // -----------------------------
                async function reverseGeocode(lat, lng) {
                    try {
                        const res = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                        const data = await res.json();
                        const fullAddress = data.display_name || '';
                        marker.bindPopup(`${fullAddress}<br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`)
                            .openPopup();
                        updateInputs(fullAddress, lat, lng);
                    } catch (err) {
                        console.error('Reverse geocode failed:', err);
                    }
                }

                // -----------------------------
                // 6. Marker setup
                // -----------------------------
                const marker = L.marker(initialLatLng, {
                        draggable: true
                    })
                    .addTo(map)
                    .bindPopup('Drag me or search location')
                    .openPopup();

                marker.on('dragend', () => {
                    const {
                        lat,
                        lng
                    } = marker.getLatLng();
                    reverseGeocode(lat, lng);
                });

                map.on('click', e => {
                    marker.setLatLng(e.latlng);
                    reverseGeocode(e.latlng.lat, e.latlng.lng);
                });

                // -----------------------------
                // 7. Geocoder (search) setup
                // -----------------------------
                L.Control.geocoder({
                        defaultMarkGeocode: false
                    })
                    .on('markgeocode', e => {
                        const center = e.geocode.center;
                        marker.setLatLng(center);
                        map.setView(center, 16);
                        marker.bindPopup(
                            `${e.geocode.name}<br>Lat: ${center.lat.toFixed(6)}<br>Lng: ${center.lng.toFixed(6)}`
                        ).openPopup();
                        updateInputs(e.geocode.name, center.lat, center.lng);
                    })
                    .addTo(map);
            }

            // -----------------------------
            // 8. Resolve initial position then init map
            // -----------------------------
            if (hasExisting) {
                initMapWithCenter([existingLat, existingLng], 24);
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const currentLat = position.coords.latitude;
                        const currentLng = position.coords.longitude;
                        initMapWithCenter([currentLat, currentLng], 24);
                    },
                    () => {
                        initMapWithCenter([fallbackLat, fallbackLng], 24);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                initMapWithCenter([fallbackLat, fallbackLng], 24);
            }
        });
    </script>
@endpush
