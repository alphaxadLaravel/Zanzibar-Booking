<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Hashids\Hashids;
use Livewire\Component;
use Livewire\WithPagination;

class UserBookingsTable extends Component
{
    use WithPagination;

    public int $userId;
    public string $search = '';
    public string $status = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);

        $bookings = Booking::where('user_id', $this->userId)
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($builder) {
                    $builder->where('booking_code', 'like', '%' . $this->search . '%')
                        ->orWhere('payment_method', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.admin.user-bookings-table', [
            'bookings' => $bookings,
            'hashids' => $hashids,
        ]);
    }
}


