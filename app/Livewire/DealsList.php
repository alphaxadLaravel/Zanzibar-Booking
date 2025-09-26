<?php

namespace App\Livewire;

use App\Models\Deal;
use Hashids\Hashids;
use Livewire\Component;
use Livewire\WithPagination;

class DealsList extends Component
{
    use WithPagination;

    // mount dealType
    public $dealType;
    public $search = '';
    public $perPage = 1;

    public function mount($dealType)
    {
        $this->dealType = $dealType;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $dealTitle = '';

        // Set deal title based on type
        switch ($this->dealType) {
            case 'hotel':
                $dealTitle = 'Hotels';
                break;
            case 'apartment':
                $dealTitle = 'Apartments';
                break;
            case 'activity':
                $dealTitle = 'Activities';
                break;
            case 'package':
                $dealTitle = 'Packages';
                break;
            case 'car':
                $dealTitle = 'Cars';
                break;
            default:
                $dealTitle = 'Deals';
        }

        // Build query with search functionality
        $query = Deal::with('category')
            ->where('type', $this->dealType);

        // Add search functionality
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('category', function ($categoryQuery) {
                      $categoryQuery->where('category', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply pagination
        $deals = $query->orderBy('created_at', 'desc')
                      ->paginate($this->perPage);

        return view('livewire.deals-list', [
            'deals' => $deals,
            'hashids' => $hashids,
            'dealTitle' => $dealTitle,
        ]);
    }
}
