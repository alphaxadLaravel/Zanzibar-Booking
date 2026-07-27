<?php

namespace App\Livewire;

use App\DTOs\HotelSearchCriteria;
use App\Services\Hotels\HotelbedsContentService;
use App\Services\Hotels\HotelSearchService;
use App\Support\FlightOfferMapper;
use App\Support\HotelOfferMapper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalHotelSearchPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $destination = '';

    public string $checkIn = '';

    public string $checkOut = '';

    public string $rooms = '';

    public string $adults = '';

    public string $children = '';

    public string $searchName = '';

    public string $sortBy = 'price_asc';

    /** @var array<int, array<string, mixed>> */
    public array $allHotels = [];

    public bool $searched = false;

    public bool $loading = false;

    public bool $browseMode = false;

    public ?string $error = null;

    /**
     * @return array{destination: string, checkIn: string, checkOut: string, rooms: string, adults: string, children: string}
     */
    protected function defaultFilters(): array
    {
        $leadDays = (int) config('hotels.defaults.lead_days', 7);
        $stayDays = (int) config('hotels.defaults.stay_days', 30);

        $checkIn = Carbon::today()->addDays(max(0, $leadDays));
        $checkOut = $checkIn->copy()->addDays(max(1, $stayDays));

        return [
            'destination' => (string) config('hotels.defaults.destination', 'ZNZ'),
            'checkIn' => $checkIn->format('Y-m-d'),
            'checkOut' => $checkOut->format('Y-m-d'),
            'rooms' => (string) config('hotels.defaults.rooms', 1),
            'adults' => (string) config('hotels.defaults.adults', 2),
            'children' => (string) config('hotels.defaults.children', 0),
        ];
    }

    protected function ensureSearchDefaults(): void
    {
        $defaults = $this->defaultFilters();

        if ($this->destination === '') {
            $this->destination = $defaults['destination'];
        }

        if ($this->checkIn === '' || $this->checkOut === '') {
            $this->checkIn = $defaults['checkIn'];
            $this->checkOut = $defaults['checkOut'];
        }

        if ($this->rooms === '') {
            $this->rooms = $defaults['rooms'];
        }

        if ($this->adults === '') {
            $this->adults = $defaults['adults'];
        }

        if ($this->children === '') {
            $this->children = $defaults['children'];
        }
    }

    public function mount(): void
    {
        if (request()->filled('destination')) {
            $this->destination = strtoupper((string) request('destination'));
        }

        if (request()->filled('checkIn')) {
            $this->checkIn = (string) request('checkIn');
        }

        if (request()->filled('checkOut')) {
            $this->checkOut = (string) request('checkOut');
        }

        if (request()->filled('rooms')) {
            $this->rooms = (string) request('rooms');
        }

        if (request()->filled('adults')) {
            $this->adults = (string) request('adults');
        }

        if (request()->filled('children')) {
            $this->children = (string) request('children');
        }

        $this->ensureSearchDefaults();
        $this->sortBy = 'price_asc';
        $this->searchHotels();
    }

    public function updatingSearchName(): void
    {
        $this->resetPage();
    }

    public function searchHotels(): void
    {
        $this->loading = true;
        $this->error = null;
        $this->searched = true;
        $this->resetPage();

        $this->ensureSearchDefaults();

        if ($this->checkIn === '' || $this->checkOut === '') {
            $defaults = $this->defaultFilters();
            $this->checkIn = $defaults['checkIn'];
            $this->checkOut = $defaults['checkOut'];
        }

        $this->searchAvailability();
    }

    protected function searchBrowse(): void
    {
        $this->loading = true;
        $this->browseMode = true;
        $this->searched = true;
        $this->sortBy = $this->sortBy === 'price_asc' || $this->sortBy === 'price_desc' ? 'name_asc' : $this->sortBy;

        try {
            $destination = $this->destination !== '' ? $this->destination : 'TZ_ALL';
            $this->allHotels = app(HotelbedsContentService::class)->browseHotels(
                $destination,
                (int) config('hotels.defaults.max_results', 200)
            );
            app(HotelbedsContentService::class)->attachImagesToHotels($this->allHotels);
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->allHotels = [];
        }

        $this->loading = false;
    }

    protected function searchAvailability(): void
    {
        $this->browseMode = false;

        $rooms = max(1, (int) ($this->rooms !== '' ? $this->rooms : 1));
        $adults = max(1, (int) ($this->adults !== '' ? $this->adults : 2));
        $children = max(0, (int) ($this->children !== '' ? $this->children : 0));
        $destination = $this->destination !== '' ? $this->destination : 'TZ_ALL';

        $validator = Validator::make([
            'destination' => $destination,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'rooms' => $rooms,
            'adults' => $adults,
            'children' => $children,
        ], [
            'destination' => ['required', 'string', 'max:12'],
            'checkIn' => ['required', 'date', 'after_or_equal:today'],
            'checkOut' => ['required', 'date', 'after:checkIn'],
            'rooms' => ['required', 'integer', 'min:1', 'max:5'],
            'adults' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['required', 'integer', 'min:0', 'max:6'],
        ]);

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            $this->loading = false;
            $this->allHotels = [];

            return;
        }

        try {
            $criteria = HotelSearchCriteria::fromArray([
                'destination' => $destination,
                'checkIn' => $this->checkIn,
                'checkOut' => $this->checkOut,
                'rooms' => $rooms,
                'adults' => $adults,
                'children' => $children,
                'maxHotels' => (int) config('hotels.defaults.max_results', 200),
            ]);

            $searchService = app(HotelSearchService::class);
            $offers = $searchService->search($criteria);
            $this->allHotels = $searchService->groupByHotel($offers);
            app(HotelbedsContentService::class)->attachImagesToHotels($this->allHotels);

            if ($this->sortBy === 'name_asc' || $this->sortBy === 'name_desc') {
                $this->sortBy = 'price_asc';
            }
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->allHotels = [];
        }

        $this->loading = false;
    }

    public function resetFilters(): void
    {
        $defaults = $this->defaultFilters();
        $this->destination = $defaults['destination'];
        $this->checkIn = $defaults['checkIn'];
        $this->checkOut = $defaults['checkOut'];
        $this->rooms = $defaults['rooms'];
        $this->adults = $defaults['adults'];
        $this->children = $defaults['children'];
        $this->searchName = '';
        $this->sortBy = 'price_asc';
        $this->error = null;
        $this->resetPage();
        $this->searchHotels();
    }

    public function updateSort(string $sortValue): void
    {
        $this->sortBy = $sortValue;
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function filteredHotels(): array
    {
        $collection = collect($this->allHotels);

        if ($this->searchName !== '') {
            $needle = strtolower($this->searchName);
            $collection = $collection->filter(
                fn (array $hotel) => str_contains(strtolower((string) ($hotel['hotel_name'] ?? '')), $needle)
            );
        }

        if ($this->browseMode) {
            $collection = match ($this->sortBy) {
                'name_desc' => $collection->sortByDesc('hotel_name'),
                default => $collection->sortBy('hotel_name'),
            };
        } else {
            $collection = match ($this->sortBy) {
                'price_desc' => $collection->sortByDesc('price'),
                'name_asc' => $collection->sortBy('hotel_name'),
                'name_desc' => $collection->sortByDesc('hotel_name'),
                default => $collection->sortBy('price'),
            };
        }

        return $collection->values()->all();
    }

    protected function buildPaginator(): LengthAwarePaginator
    {
        $items = $this->filteredHotels();
        $perPage = 6;
        $page = $this->getPage();

        $slice = collect($items)->forPage($page, $perPage)->values();
        $defaultImage = HotelOfferMapper::defaultHotelImage();

        $enriched = $slice->map(function (array $hotel) use ($defaultImage) {
            $code = (string) ($hotel['hotel_code'] ?? '');
            $currency = strtoupper((string) ($hotel['currency'] ?? 'USD'));
            $price = (float) ($hotel['price'] ?? 0);
            $isBrowse = (bool) ($hotel['browse_only'] ?? $this->browseMode);

            $hotel['image_url'] = $hotel['image_url'] ?? $defaultImage;
            $hotel['star_rating'] = HotelOfferMapper::categoryStars($hotel['category_code'] ?? null) ?? 4;
            $hotel['view_route'] = route('hotels.global.show', ['hotelCode' => $code]);
            $hotel['display_price'] = $isBrowse || $price <= 0
                ? null
                : $currency . ' ' . FlightOfferMapper::formatPrice($price);
            $hotel['browse_only'] = $isBrowse;
            $hotel['title'] = (string) ($hotel['hotel_name'] ?? 'Hotel');
            $hotel['location'] = (string) ($hotel['destination_name'] ?? $this->destination ?: 'Tanzania');
            $hotel['lat'] = $hotel['latitude'] ?? null;
            $hotel['long'] = $hotel['longitude'] ?? null;
            $hotel['id'] = $code;

            return $hotel;
        });

        return new LengthAwarePaginator(
            $enriched,
            count($items),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    public function render()
    {
        $contentService = app(HotelbedsContentService::class);

        return view('livewire.global-hotel-search-page', [
            'destinationOptions' => $contentService->destinationOptionsForSearch(),
            'hotels' => $this->searched && ! $this->loading ? $this->buildPaginator() : new LengthAwarePaginator([], 0, 6),
        ]);
    }
}
