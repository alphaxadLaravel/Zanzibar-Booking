<?php

namespace App\Livewire\Admin;

use App\Models\Deal;
use Livewire\Component;
use Livewire\WithPagination;

class UserDealsTable extends Component
{
    use WithPagination;

    public int $userId;
    public string $search = '';
    public string $type = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $deals = Deal::where('user_id', $this->userId)
            ->when($this->type !== 'all', fn ($query) => $query->where('type', $this->type))
            ->when($this->search, function ($query) {
                $query->where(function ($builder) {
                    $builder->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('livewire.admin.user-deals-table', [
            'deals' => $deals,
        ]);
    }
}


