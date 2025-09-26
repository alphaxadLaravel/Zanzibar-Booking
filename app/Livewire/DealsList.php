<?php

namespace App\Livewire;

use App\Models\Deal;
use Hashids\Hashids;
use Livewire\Component;

class DealsList extends Component
{

    // mount dealType
    public $dealType;
    public function mount($dealType)
    {
        $this->dealType = $dealType;
    }

    public function render()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $deals = [];
        $dealTitle = '';

        if ($this->dealType == 'hotel') {
            $deals = Deal::with('category')
                ->where('type', $this->dealType)
                ->orderBy('created_at', 'desc')
                ->get();
            $dealTitle = 'Hotels';
        }

        if ($this->dealType == 'apartment') {
            $deals = Deal::with('category')
                ->where('type', $this->dealType)
                ->orderBy('created_at', 'desc')
                ->get();

            $dealTitle = 'Apartments';
        }

        if ($this->dealType == 'activity') {
            $deals = Deal::with('category')
                ->where('type', $this->dealType)
                ->orderBy('created_at', 'desc')
                ->get();

            $dealTitle = 'Activities';
        }

        if ($this->dealType == 'package') {
            $deals = Deal::with('category')
                ->where('type', $this->dealType)
                ->orderBy('created_at', 'desc')
                ->get();

            $dealTitle = 'Packages';
        }
        
        if ($this->dealType == 'car') {
            $deals = Deal::with('category')
                ->where('type', $this->dealType)
                ->orderBy('created_at', 'desc')
                ->get();

            $dealTitle = 'Cars';
        }


        return view('livewire.deals-list', [
            'deals' => $deals,
            'hashids' => $hashids,
            'dealTitle' => $dealTitle,
        ]);
    }
}
