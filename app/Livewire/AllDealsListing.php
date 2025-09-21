<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Deal;
use Livewire\Component;
use Hashids\Hashids;

class AllDealsListing extends Component
{
    // mount deal type
    public $dealType;
    
    // Filter properties
    public $searchLocation = '';
    public $searchCategory = '';
    public $searchName = '';
    public $sortBy = 'new';

    public function mount($dealType)
    {
        $this->dealType = $dealType;
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
    }
    
    /**
     * Update sort
     */
    public function updateSort($sortValue)
    {
        $this->sortBy = $sortValue;
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
     * Get the appropriate price unit for a deal type
     */
    public function getPriceUnit($dealType)
    {
        switch ($dealType) {
            case 'hotel':
            case 'apartment':
                return '/night';
            case 'tour':
                return '/person';
            case 'car':
                return '/day';
            default:
                return '';
        }
    }

    /**
     * Get default image for deal type
     */
    public function getDefaultImage($dealType)
    {
        switch ($dealType) {
            case 'hotel':
                return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&h=240&fit=crop&crop=center';
            case 'apartment':
                return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=360&h=240&fit=crop&crop=center';
            case 'tour':
                return 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=360&h=240&fit=crop&crop=center';
            case 'car':
                return 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=360&h=240&fit=crop&crop=center';
            default:
                return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&h=240&fit=crop&crop=center';
        }
    }

    public function render()
    {
        // Build the query with appropriate relationships based on deal type
        $query = Deal::where('type', $this->dealType);
        
        // Apply filters
        if ($this->searchLocation) {
            $query->where('location', 'like', '%' . $this->searchLocation . '%');
        }
        
        if ($this->searchCategory) {
            $query->where('category_id', $this->searchCategory);
        }
        
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
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Add specific relationships based on deal type
        switch ($this->dealType) {
            case 'hotel':
            case 'apartment':
                $query->with(['category', 'photos', 'rooms.photos']);
                break;
            case 'tour':
                $query->with(['category', 'photos', 'tours']);
                break;
            case 'car':
                $query->with(['category', 'photos', 'car']);
                break;
            default:
                $query->with(['category', 'photos']);
                break;
        }

        $deals = $query->paginate(6);

        $categories = Category::where('type', $this->dealType)
            ->get();

        $locations = Deal::where('type', $this->dealType)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        $hashids = $this->getHashids();

        // Prepare deal data with routes and additional info
        $dealsWithRoutes = $deals->getCollection()->map(function ($deal) {
            $deal->view_route = $this->getViewRoute($deal);
            $deal->default_image = $this->getDefaultImage($deal->type);
            return $deal;
        });

        return view('livewire.all-deals-listing', [
            'deals' => $deals,
            'categories' => $categories,
            'locations' => $locations,
            'dealType' => $this->dealType,
            'hashids' => $hashids,
            'priceUnit' => $this->getPriceUnit($this->dealType),
        ]);
    }
}
