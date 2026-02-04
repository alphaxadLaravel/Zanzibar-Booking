@push('scripts')
<script>
// Global map management to prevent conflicts and improve performance
window.ZanzibarMapManager = {
    maps: new Map(),
    initialized: false,
    
    // Initialize Google Maps API
    initGoogleMaps() {
        if (this.initialized) return Promise.resolve();
        
        return new Promise((resolve, reject) => {
            if (typeof google !== 'undefined' && google.maps) {
                this.initialized = true;
                resolve();
                return;
            }
            
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=geometry&callback=window.ZanzibarMapManager.onGoogleMapsLoaded`;
            script.async = true;
            script.defer = true;
            
            window.ZanzibarMapManager.onGoogleMapsLoaded = () => {
                this.initialized = true;
                resolve();
            };
            
            script.onerror = () => reject(new Error('Failed to load Google Maps API'));
            document.head.appendChild(script);
        });
    },
    
    // Create or get map instance
    async getMap(containerId, options = {}) {
        await this.initGoogleMaps();
        
        if (this.maps.has(containerId)) {
            return this.maps.get(containerId);
        }
        
        const container = document.getElementById(containerId);
        if (!container) {
            throw new Error(`Map container ${containerId} not found`);
        }
        
        const defaultOptions = {
            zoom: 8,
            center: { lat: -6.1659, lng: 39.2026 }, // Zanzibar coordinates
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ],
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            gestureHandling: 'cooperative'
        };
        
        const mapOptions = { ...defaultOptions, ...options };
        const map = new google.maps.Map(container, mapOptions);
        
        this.maps.set(containerId, {
            map: map,
            markers: [],
            infoWindows: []
        });
        
        return this.maps.get(containerId);
    },
    
    // Update markers for a specific map
    async updateMarkers(containerId, deals, options = {}) {
        const mapInstance = await this.getMap(containerId);
        if (!mapInstance) return;
        
        // Clear existing markers and info windows
        mapInstance.markers.forEach(marker => marker.setMap(null));
        mapInstance.infoWindows.forEach(infoWindow => infoWindow.close());
        mapInstance.markers = [];
        mapInstance.infoWindows = [];
        
        if (!deals || deals.length === 0) return;
        
        const bounds = new google.maps.LatLngBounds();
        let validMarkers = 0;
        
        deals.forEach(deal => {
            if (!deal.lat || !deal.long) return;
            
            const position = { 
                lat: parseFloat(deal.lat), 
                lng: parseFloat(deal.long) 
            };
            
            // Create marker with custom icon
            const marker = new google.maps.Marker({
                position: position,
                map: mapInstance.map,
                title: deal.title,
                icon: {
                    url: this.getMarkerIcon(deal.type),
                    scaledSize: new google.maps.Size(30, 30),
                    anchor: new google.maps.Point(15, 15)
                },
                animation: google.maps.Animation.DROP
            });
            
            // Create info window
            const infoWindow = new google.maps.InfoWindow({
                content: this.createInfoWindowContent(deal)
            });
            
            // Add click listener
            marker.addListener('click', () => {
                // Close other info windows
                mapInstance.infoWindows.forEach(iw => iw.close());
                infoWindow.open(mapInstance.map, marker);
            });
            
            mapInstance.markers.push(marker);
            mapInstance.infoWindows.push(infoWindow);
            bounds.extend(position);
            validMarkers++;
        });
        
        // Fit map to show all markers
        if (validMarkers > 0) {
            if (validMarkers === 1) {
                mapInstance.map.setCenter(bounds.getCenter());
                mapInstance.map.setZoom(12);
            } else {
                mapInstance.map.fitBounds(bounds);
                // Ensure minimum zoom level
                google.maps.event.addListenerOnce(mapInstance.map, 'bounds_changed', () => {
                    if (mapInstance.map.getZoom() > 15) {
                        mapInstance.map.setZoom(15);
                    }
                });
            }
        }
        
        // Add card click listeners
        this.addCardClickListeners(containerId, mapInstance);
    },
    
    // Get marker icon based on deal type
    getMarkerIcon(dealType) {
        const icons = {
            'hotel': 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            'apartment': 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            'tour': 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            'car': 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
        };
        return icons[dealType] || icons['hotel'];
    },
    
    // Create info window content
    createInfoWindowContent(deal) {
        const imageUrl = deal.cover_photo ? 
            `storage/${deal.cover_photo}` : 
            (deal.photos && deal.photos.length > 0 ? 
                `storage/${deal.photos[0].photo}` : 
                'images/default-placeholder.jpg');
        
        const priceUnit = deal.type === 'hotel' || deal.type === 'apartment' ? 'per night' :
                         deal.type === 'tour' ? 'per person' :
                         deal.type === 'car' ? 'per day' : '';
        
        return `
            <div style="padding: 10px; max-width: 250px; font-family: Arial, sans-serif;">
                <img src="${imageUrl}" alt="${deal.title}" 
                     style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                <h6 style="margin: 0 0 8px 0; font-weight: 600; color: #333;">${deal.title}</h6>
                <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;">
                    <i class="fas fa-map-marker-alt"></i> ${deal.location}
                </p>
                <p style="margin: 0 0 8px 0; color: #2e8b57; font-weight: 600;">
                    ${deal.display_price || ('USD ' + parseFloat(deal.base_price).toFixed(2))} ${priceUnit}
                </p>
                ${deal.category ? `<span style="background: #2e8b57; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px;">${deal.category.category}</span>` : ''}
                <div style="margin-top: 10px;">
                    <a href="${deal.view_route}" style="background: #003580; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px;">View Details</a>
                </div>
            </div>
        `;
    },
    
    // Add click listeners to deal cards
    addCardClickListeners(containerId, mapInstance) {
        // Remove existing listeners to prevent duplicates
        const existingListeners = mapInstance.cardListeners || [];
        existingListeners.forEach(listener => listener.remove());
        mapInstance.cardListeners = [];
        
        mapInstance.markers.forEach((marker, index) => {
            const dealCard = document.querySelector(`[data-id="${mapInstance.deals[index]?.id}"]`);
            if (dealCard) {
                const listener = dealCard.addEventListener('click', () => {
                    mapInstance.map.setCenter(marker.getPosition());
                    mapInstance.map.setZoom(15);
                    mapInstance.infoWindows[index].open(mapInstance.map, marker);
                });
                mapInstance.cardListeners.push(listener);
            }
        });
    },
    
    // Destroy map instance
    destroyMap(containerId) {
        const mapInstance = this.maps.get(containerId);
        if (mapInstance) {
            mapInstance.markers.forEach(marker => marker.setMap(null));
            mapInstance.infoWindows.forEach(infoWindow => infoWindow.close());
            this.maps.delete(containerId);
        }
    }
};

// Livewire integration
document.addEventListener('livewire:load', function () {
    // Initialize maps when Livewire loads
    window.ZanzibarMapManager.initGoogleMaps();
});

document.addEventListener('livewire:update', function () {
    // Update maps when Livewire updates
    window.ZanzibarMapManager.maps.forEach(async (mapInstance, containerId) => {
        try {
            // Try to get deals data from the Livewire component
            const livewireComponent = window.Livewire.find(document.querySelector(`#${containerId}`).closest('[wire\\:id]')?.getAttribute('wire:id'));
            if (livewireComponent && livewireComponent.dealsData) {
                await window.ZanzibarMapManager.updateMarkers(containerId, livewireComponent.dealsData);
            }
        } catch (error) {
            console.error('Failed to update map markers:', error);
        }
    });
});
</script>
@endpush
