<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Deal;
use Livewire\Component;
use Livewire\WithPagination;
use Hashids\Hashids;

class SearchResults extends Component
{
    use WithPagination;
    
    // Filter properties
    public $searchLocation = '';
    public $searchCategory = '';
    public $searchName = '';
    public $sortBy = 'new';
    
    // Deals data for JavaScript access
    public $dealsData = [];

    public function mount($location = '', $category = '', $name = '')
    {
        $hashids = $this->getHashids();
        
        $this->searchLocation = $location;
        $this->searchName = $name;
        
        // Decode category hashid if provided
        if ($category) {
            $decodedIds = $hashids->decode($category);
            if (!empty($decodedIds)) {
                $this->searchCategory = (string)$decodedIds[0];
            }
        } else {
            $this->searchCategory = '';
        }
    }
    
    /**
     * Reset pagination when filters change
     */
    public function updatingSearchCategory()
    {
        $this->resetPage();
    }
    
    public function updatingSearchLocation()
    {
        $this->resetPage();
    }
    
    public function updatingSearchName()
    {
        $this->resetPage();
    }
    
    /**
     * Reset filters
     */
    public function resetFilters()
    {
        $this->searchLocation = '';
        $this->searchCategory = '';
        $this->searchName = '';
        $this->sortBy = 'new';
        $this->resetPage();
    }
    
    /**
     * Update sort
     */
    public function updateSort($sortValue)
    {
        $this->sortBy = $sortValue;
        $this->resetPage();
    }
    
    /**
     * Create Hashids instance
     */
    private function getHashids()
    {
        return new Hashids('MchungajiZanzibarBookings', 10);
    }

    /**
     * Get the appropriate view route for a deal
     */
    public function getViewRoute($deal)
    {
        $hashids = $this->getHashids();
        $encodedId = $hashids->encode($deal->id);
        
        switch ($deal->type) {
            case 'hotel':
                return route('view-hotel', ['id' => $encodedId]);
            case 'apartment':
                return route('view-apartment', ['id' => $encodedId]);
            case 'tour':
                return route('view-tour', ['id' => $encodedId]);
            case 'car':
                return route('view-car', ['id' => $encodedId]);
            default:
                return '#';
        }
    }

    /**
     * Get default image for deal type
     */
    public function getDefaultImage($dealType)
    {
        switch ($dealType) {
            case 'hotel':
                return asset('images/default-hotel.jpg');
            case 'apartment':
                return asset('images/default-apartment.jpg');
            case 'tour':
                return asset('images/default-tour.jpg');
            case 'car':
                return asset('images/default-car.jpg');
            default:
                return asset('images/default-placeholder.jpg');
        }
    }

    public function render()
    {
        // Start building the query
        $query = Deal::active();

        // Apply location filter
        if ($this->searchLocation) {
            $query->where('location', 'like', '%' . $this->searchLocation . '%');
        }

        // Apply category filter
        if ($this->searchCategory) {
            $query->where('category_id', $this->searchCategory);
        }

        // Apply name/title filter
        if ($this->searchName) {
            $query->where('title', 'like', '%' . $this->searchName . '%');
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name_a_z':
                $query->orderBy('title', 'asc');
                break;
            case 'name_z_a':
                $query->orderBy('title', 'desc');
                break;
            case 'new':
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
        }

        // Get all matching deals with relationships
        $deals = $query->with(['category', 'photos'])
            ->paginate(12);

        // Get categories for filter dropdown
        $categories = Category::whereIn('type', ['hotel', 'apartment', 'tour', 'car'])
            ->get();

        // Get locations for filter dropdown
        $locations = Deal::active()
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        $hashids = $this->getHashids();

        // Prepare deal data with routes and default images
        $deals->getCollection()->transform(function ($deal) use ($hashids) {
            $deal->view_route = $this->getViewRoute($deal);
            $deal->default_image = $this->getDefaultImage($deal->type);
            return $deal;
        });

        // Store deals data for JavaScript access (only the items, not the pagination object)
        $this->dealsData = $deals->items();

        return view('livewire.search-results', [
            'deals' => $deals,
            'categories' => $categories,
            'locations' => $locations,
            'hashids' => $hashids,
        ]);
    }
}
