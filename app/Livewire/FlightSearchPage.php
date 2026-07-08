<?php

namespace App\Livewire;

use App\DTOs\FlightSearchCriteria;
use App\Http\Requests\FlightSearchRequest;
use App\Services\Flights\AffiliateTrackingService;
use App\Services\Flights\FlightSearchService;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class FlightSearchPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $tripType = 'one_way';
    public string $origin = 'ZNZ';
    public string $destination = 'DAR';
    public string $departureDate = '';
    public ?string $returnDate = null;
    public int $adults = 1;
    public int $children = 0;
    public int $infants = 0;
    public string $travelClass = 'ECONOMY';
    public bool $nonStop = false;

    public array $flights = [];
    public bool $searched = false;
    public bool $loading = false;
    public bool $browseMode = true;
    public ?string $error = null;
    public ?int $lastSearchId = null;

    public string $filterAirline = '';
    public string $filterRoute = '';
    public string $sortBy = 'price_asc';

    public ?array $selectedFlight = null;

    public function mount(): void
    {
        $this->departureDate = now()
            ->addDays((int) config('flights.featured.days_ahead', 7))
            ->format('Y-m-d');

        if (request()->filled('origin')) {
            $this->origin = strtoupper(request('origin', 'ZNZ'));
            $this->destination = strtoupper(request('destination', ''));
            $this->departureDate = request('departureDate', $this->departureDate);
            $this->returnDate = request('returnDate');
            $this->adults = (int) request('adults', 1);
            $this->children = (int) request('children', 0);
            $this->infants = (int) request('infants', 0);
            $this->travelClass = strtoupper(request('travelClass', 'ECONOMY'));
            $this->tripType = request('tripType', 'one_way');
            $this->browseMode = false;
            $this->searchFlights();

            return;
        }

        // Featured flights load after the page renders (wire:init) for faster first paint.
    }

    public function loadFeaturedFlights(): void
    {
        if (! $this->browseMode || $this->loading) {
            return;
        }

        $this->loading = true;
        $this->browseMode = true;
        $this->error = null;
        $this->filterAirline = '';
        $this->filterRoute = '';
        $this->sortBy = 'price_asc';

        try {
            $this->flights = app(FlightSearchService::class)->searchFeaturedFlights($this->departureDate);
            $this->searched = true;
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->flights = [];
            $this->searched = true;
        } finally {
            $this->loading = false;
        }
    }

    public function updatedDepartureDate(): void
    {
        if ($this->browseMode && ! $this->loading) {
            $this->loadFeaturedFlights();
        }
    }

    public function resetSearch(): void
    {
        $this->tripType = 'one_way';
        $this->origin = 'ZNZ';
        $this->destination = 'DAR';
        $this->returnDate = null;
        $this->adults = 1;
        $this->children = 0;
        $this->infants = 0;
        $this->travelClass = 'ECONOMY';
        $this->nonStop = false;
        $this->filterAirline = '';
        $this->filterRoute = '';
        $this->sortBy = 'price_asc';
        $this->departureDate = now()
            ->addDays((int) config('flights.featured.days_ahead', 7))
            ->format('Y-m-d');
        $this->loadFeaturedFlights();
    }

    public function updatedTripType(): void
    {
        if ($this->tripType !== 'round_trip') {
            $this->returnDate = null;
        }
    }

    public function searchFlights(): void
    {
        $this->loading = true;
        $this->browseMode = false;
        $this->error = null;
        $this->filterAirline = '';
        $this->filterRoute = '';
        $this->resetPage();

        $payload = [
            'tripType' => $this->tripType,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departureDate' => $this->departureDate,
            'returnDate' => $this->returnDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'travelClass' => $this->travelClass,
            'nonStop' => $this->nonStop,
        ];

        $validator = Validator::make($payload, (new FlightSearchRequest())->rules());

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            $this->flights = [];
            $this->searched = true;
            $this->loading = false;

            return;
        }

        try {
            $criteria = FlightSearchCriteria::fromArray($payload);
            $this->flights = app(FlightSearchService::class)->search($criteria);
            $this->lastSearchId = session('last_flight_search_id');
            $this->searched = true;
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->flights = [];
            $this->searched = true;
        } finally {
            $this->loading = false;
        }
    }

    public function bookFlight(string $flightId): mixed
    {
        $flight = $this->findFlight($flightId);

        if (! $flight) {
            $this->dispatch('notify', type: 'error', message: 'Flight not found. Please search again.');

            return null;
        }

        try {
            app(AffiliateTrackingService::class)->logClick([
                'flight_search_id' => $this->lastSearchId ?? session('last_flight_search_id'),
                'airline' => $flight['airline'] ?? null,
                'flight_number' => $flight['flight_number'] ?? null,
                'origin' => $flight['departure']['airport'] ?? $this->origin,
                'destination' => $flight['arrival']['airport'] ?? $this->destination,
                'price' => $flight['price'] ?? null,
                'currency' => $flight['currency'] ?? 'USD',
                'affiliate_name' => $flight['affiliate_name'] ?? 'Duffel',
                'affiliate_url' => route('flights.checkout', ['offerId' => $flight['id']]),
            ]);
        } catch (\Throwable $e) {
            // Don't block checkout if analytics logging fails.
        }

        return redirect()->route('flights.checkout', ['offerId' => $flight['id']]);
    }

    public function showFlightDetails(string $flightId): void
    {
        $flight = $this->findFlight($flightId);

        if (! $flight) {
            $this->dispatch('notify', type: 'error', message: 'Flight details not found. Please search again.');

            return;
        }

        $this->selectedFlight = $flight;
    }

    public function closeFlightDetails(): void
    {
        $this->selectedFlight = null;
    }

    protected function findFlight(string $flightId): ?array
    {
        return collect($this->flights)->firstWhere('id', $flightId)
            ?? collect(session('flight_search_results', []))->firstWhere('id', $flightId);
    }

    public function getFilteredFlightsProperty(): array
    {
        $flights = collect($this->flights);

        if ($this->filterAirline !== '') {
            $flights = $flights->filter(fn ($f) => ($f['airline'] ?? '') === $this->filterAirline);
        }

        if ($this->browseMode && $this->filterRoute !== '') {
            $flights = $flights->filter(fn ($f) => ($f['route_label'] ?? '') === $this->filterRoute);
        }

        $flights = match ($this->sortBy) {
            'price_desc' => $flights->sortByDesc('price'),
            'duration_asc' => $flights->sortBy('duration'),
            'stops_asc' => $flights->sortBy('stops'),
            default => $flights->sortBy('price'),
        };

        return $flights->values()->all();
    }

    public function getAirlineOptionsProperty(): array
    {
        return collect($this->flights)->pluck('airline')->filter()->unique()->sort()->values()->all();
    }

    public function getRouteOptionsProperty(): array
    {
        return collect($this->flights)
            ->pluck('route_label')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.flight-search-page', [
            'displayFlights' => $this->filteredFlights,
            'flights' => $this->flights,
            'airlineOptions' => $this->airlineOptions,
            'airportOptions' => config('flights.airport_options', []),
            'routeOptions' => $this->routeOptions,
            'hasActiveFilters' => $this->filterAirline !== '' || ($this->browseMode && $this->filterRoute !== ''),
        ]);
    }
}
