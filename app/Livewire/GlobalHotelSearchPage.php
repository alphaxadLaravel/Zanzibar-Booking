<?php

namespace App\Livewire;

use App\DTOs\HotelSearchCriteria;
use App\Services\Hotels\HotelSearchService;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class GlobalHotelSearchPage extends Component
{
    public string $destination = 'ZNZ';
    public string $checkIn = '';
    public string $checkOut = '';
    public int $rooms = 1;
    public int $adults = 2;
    public int $children = 0;

    /** @var array<int, array<string, mixed>> */
    public array $hotels = [];

    public bool $searched = false;
    public bool $loading = false;
    public ?string $error = null;
    public string $sortBy = 'price_asc';

    public function mount(): void
    {
        $this->checkIn = now()->addDays(7)->format('Y-m-d');
        $this->checkOut = now()->addDays(9)->format('Y-m-d');

        if (request()->filled('destination')) {
            $this->destination = strtoupper(request('destination', 'ZNZ'));
            $this->checkIn = request('checkIn', $this->checkIn);
            $this->checkOut = request('checkOut', $this->checkOut);
            $this->rooms = max(1, (int) request('rooms', 1));
            $this->adults = max(1, (int) request('adults', 2));
            $this->children = max(0, (int) request('children', 0));
            $this->searchHotels();
        }
    }

    public function searchHotels(): void
    {
        $this->loading = true;
        $this->error = null;
        $this->searched = true;

        $validator = Validator::make([
            'destination' => $this->destination,
            'checkIn' => $this->checkIn,
            'checkOut' => $this->checkOut,
            'rooms' => $this->rooms,
            'adults' => $this->adults,
            'children' => $this->children,
        ], [
            'destination' => ['required', 'string', 'max:10'],
            'checkIn' => ['required', 'date', 'after_or_equal:today'],
            'checkOut' => ['required', 'date', 'after:checkIn'],
            'rooms' => ['required', 'integer', 'min:1', 'max:5'],
            'adults' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['required', 'integer', 'min:0', 'max:6'],
        ]);

        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            $this->loading = false;
            $this->hotels = [];

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
            ]);

            $searchService = app(HotelSearchService::class);
            $offers = $searchService->search($criteria);
            $this->hotels = $searchService->groupByHotel($offers);
            $this->applySort();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->hotels = [];
        }

        $this->loading = false;
    }

    public function updatedSortBy(): void
    {
        $this->applySort();
    }

    protected function applySort(): void
    {
        $collection = collect($this->hotels);

        $this->hotels = match ($this->sortBy) {
            'price_desc' => $collection->sortByDesc('price')->values()->all(),
            'name_asc' => $collection->sortBy('hotel_name')->values()->all(),
            default => $collection->sortBy('price')->values()->all(),
        };
    }

    public function render()
    {
        return view('livewire.global-hotel-search-page', [
            'destinationOptions' => config('hotels.destination_options', []),
        ]);
    }
}
