<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
 #[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    protected $queryString = ['search', 'status'];

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
        $orders = Order::with(['user', 'address', 'payment', 'shiprocket'])
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);
        // dd($orders);

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'canceled', 'returned']; // Based on your model

        return view('livewire.admin.order.index', compact('orders', 'statuses'))
            ;
    }
}







