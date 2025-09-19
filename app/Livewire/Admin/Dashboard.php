<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariantCombination;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    public $customers;
    public $orders;
    public $revenue;
    public $averageRevenue;

    public $CurrentOrder;

    public $outOFstock;

    #[Layout('components.layouts.admin')]

    public function mount()
    {
        // Cache queries for 15 minutes to reduce database load
        $this->customers = Cache::remember('dashboard_customers', 900, fn() => User::count());
        $this->orders = Cache::remember('dashboard_orders', 900, fn() => Order::count());
        $this->revenue = Cache::remember('dashboard_revenue', 900, fn() => Order::sum('total_amount'));
        $this->averageRevenue = Cache::remember('dashboard_average_revenue', 900, fn() => Order::avg('total_amount'));
        $this->CurrentOrder = OrderItem::select('order_id', 'user_id', 'quantity')
            ->orderBy('order_id', 'desc')
            ->limit(3)
            ->get();

        $this->outOFstock = ProductVariantCombination::where('stock', '<', 5)->get();




    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}