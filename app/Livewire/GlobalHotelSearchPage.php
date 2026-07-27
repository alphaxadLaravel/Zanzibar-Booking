<?php

namespace App\Livewire;

use App\DTOs\HotelSearchCriteria;
use App\Services\Hotels\HotelbedsContentService;
use App\Services\Hotels\HotelSearchService;
use App\Support\FlightOfferMapper;
use App\Support\HotelOfferMapper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class GlobalHotelSearchPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $destination = 'TZ_ALL';

    public string $checkIn = '';

    public string $checkOut = '';

    public int $rooms = 1;

    public int $adults = 2;

    public int $children = 0;

    public string $searchName = '';

    public string $sortBy = 'price_asc';

    /** @var array<int, array<string, mixed>> */
    public array $allHotels = [];

    public bool $searched = false;

    public bool $loading = false;

    public ?string $error = null;

    public function mount(): void
    {
        $this->checkIn = now()->addDays(7)->format('Y-m-d');
        $this->checkOut = now()->addDays(9)->format('Y-m-d');

        if (request()->filled('destination')) {
            $this->destination = strtoupper((string) request('destination', 'TZ_ALL'));
        }

        if (request()->filled('checkIn')) {
            $this->checkIn = (string) request('checkIn');
        }

        if (request()->filled('checkOut')) {
            $this->checkOut = (string) request('checkOut');
        }

        $this->rooms = max(1, (int) request('rooms', 1));
        $this->adults = max(1, (int) request('adults', 2));
        $this->children = max(0, (int) request('children', 0));

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

        $validator = Validator::make([
            'destination' => $this->destination,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'rooms' => $this->rooms,
            'adults' => $this->adults,
            'children' => $this->children,
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
                'destination' => $this->destination,
                'checkIn' => $this->checkIn,
                'checkOut' => $this->checkOut,
                'rooms' => $this->rooms,
                'adults' => $this->adults,
                'children' => $this->children,
                'maxHotels' => (int) config('hotels.defaults.max_results', 200),
            ]);

            $searchService = app(HotelSearchService::class);
            $offers = $searchService->search($criteria);
            $this->allHotels = $searchService->groupByHotel($offers);
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->allHotels = [];
        }

        $this->loading = false;
    }

    public function resetFilters(): void
    {
        $this->destination = 'TZ_ALL';
        $this->checkIn = now()->addDays(7)->format('Y-m-d');
        $this->checkOut = now()->addDays(9)->format('Y-m-d');
        $this->rooms = 1;
        $this->adults = 2;
        $this->children = 0;
        $this->searchName = '';
        $this->sortBy = 'price_asc';
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

        $collection = match ($this->sortBy) {
            'price_desc' => $collection->sortByDesc('price'),
            'name_asc' => $collection->sortBy('hotel_name'),
            'name_desc' => $collection->sortByDesc('hotel_name'),
            default => $collection->sortBy('price'),
        };

        return $collection->values()->all();
    }

    protected function buildPaginator(): LengthAwarePaginator
    {
        $items = $this->filteredHotels();
        $perPage = 6;
        $page = $this->getPage();

        $slice = collect($items)->forPage($page, $perPage)->values();
        $codes = $slice->pluck('hotel_code')->filter()->all();
        $images = app(HotelbedsContentService::class)->imagesForHotels($codes);

        $enriched = $slice->map(function (array $hotel) use ($images) {
            $code = (string) ($hotel['hotel_code'] ?? '');
            $currency = strtoupper((string) ($hotel['currency'] ?? 'USD'));
            $price = (float) ($hotel['price'] ?? 0);

            $hotel['image_url'] = $images[$code] ?? HotelOfferMapper::defaultHotelImage();
            $hotel['star_rating'] = HotelOfferMapper::categoryStars($hotel['category_code'] ?? null) ?? 4;
            $hotel['view_route'] = route('hotels.global.show', ['hotelCode' => $code]);
            $hotel['display_price'] = $currency . ' ' . FlightOfferMapper::formatPrice($price);
            $hotel['title'] = (string) ($hotel['hotel_name'] ?? 'Hotel');
            $hotel['location'] = (string) ($hotel['destination_name'] ?? $this->destination);
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
