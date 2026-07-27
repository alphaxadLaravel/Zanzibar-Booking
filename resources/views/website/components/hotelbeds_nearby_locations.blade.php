{{-- Nearby locations from Hotelbeds Content API (terminals + interest points) --}}
@php
    $nearbyLocations = $profile['nearby_locations'] ?? [];
@endphp

@if($nearbyLocations !== [])
    <hr>
    <section class="nearby-locations">
        <h4 class="section-title mb-3">Nearby Locations</h4>
        <div class="section-content">
            <div class="d-flex flex-wrap" style="gap: 10px;">
                @foreach($nearbyLocations as $location)
                    @php
                        $iconMap = [
                            'Airport' => 'mdi-airplane',
                            'Ferry Port' => 'mdi-ferry',
                            'Beach' => 'mdi-beach',
                            'School' => 'mdi-school',
                            'Hospital' => 'mdi-hospital',
                            'Shopping Center' => 'mdi-shopping',
                            'Restaurant' => 'mdi-food',
                            'Bank' => 'mdi-bank',
                            'ATM' => 'mdi-credit-card',
                            'Gas Station' => 'mdi-gas-station',
                            'Bus Station' => 'mdi-bus',
                            'Train Station' => 'mdi-train',
                            'Tourist Attraction' => 'mdi-camera',
                            'Market' => 'mdi-store',
                            'Pharmacy' => 'mdi-pill',
                            'Police Station' => 'mdi-shield',
                            'Post Office' => 'mdi-mail',
                            'Gym' => 'mdi-dumbbell',
                            'Park' => 'mdi-tree',
                            'Mosque' => 'mdi-mosque',
                            'Church' => 'mdi-church',
                        ];
                        $icon = $iconMap[$location['category'] ?? ''] ?? 'mdi-map-marker';
                    @endphp
                    <div class="facility-card d-flex align-items-center px-3 py-2 mb-2"
                        style="background: #fff; border-radius: 6px; border: 1px solid #e0e0e0; min-height: 38px; flex: 0 0 auto; min-width: 180px; max-width: 320px; width: calc(100%/6 - 10px);">
                        <span class="me-2" style="width: 22px; text-align: center;">
                            <i class="mdi {{ $icon }}" style="font-size: 1.2rem; color: #2e8b57;"></i>
                        </span>
                        <span style="font-size: 13px; font-weight: 500; color: #333; line-height: 1.3; flex:1;">
                            {{ $location['name'] }}
                        </span>
                        @if(!empty($location['formatted_distance']))
                            <span class="ms-2 text-nowrap" style="font-size: 13px; color: #2e8b57; font-weight: 600;">
                                {{ $location['formatted_distance'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
