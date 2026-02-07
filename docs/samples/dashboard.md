# Admin Dashboard

A complete admin dashboard with stat cards, charts, recent activity, and data tables.

## Preview

<div style="background: #f3f4f6; padding: 20px; border-radius: 12px;">
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px;">
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-size: 12px; color: #6b7280;">Total Revenue</div>
      <div style="font-size: 24px; font-weight: 700; margin: 4px 0;">$45,231</div>
      <div style="font-size: 12px; color: #22c55e;">+12.5% from last month</div>
    </div>
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-size: 12px; color: #6b7280;">Orders</div>
      <div style="font-size: 24px; font-weight: 700; margin: 4px 0;">1,234</div>
      <div style="font-size: 12px; color: #22c55e;">+8.2% from last month</div>
    </div>
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-size: 12px; color: #6b7280;">Customers</div>
      <div style="font-size: 24px; font-weight: 700; margin: 4px 0;">567</div>
      <div style="font-size: 12px; color: #22c55e;">+15.3% from last month</div>
    </div>
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-size: 12px; color: #6b7280;">Conversion</div>
      <div style="font-size: 24px; font-weight: 700; margin: 4px 0;">3.2%</div>
      <div style="font-size: 12px; color: #ef4444;">-2.1% from last month</div>
    </div>
  </div>
  <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-weight: 600; margin-bottom: 12px;">Revenue Overview</div>
      <div style="height: 120px; background: linear-gradient(to top, rgba(99, 102, 241, 0.1), transparent); border-radius: 4px;"></div>
    </div>
    <div style="background: white; padding: 16px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
      <div style="font-weight: 600; margin-bottom: 12px;">Recent Activity</div>
      <div style="font-size: 14px; color: #6b7280;">John created a new order</div>
    </div>
  </div>
</div>

## Full Code

```vue
<script setup>
import { computed } from 'vue'
import {
    AdminLayout,
    PageHeader,
    StatCard,
    StatGrid,
    Card,
    LineChart,
    DonutChart,
    DataTable,
    Avatar,
    Badge,
    Button,
    Timeline,
    TimelineItem
} from '@cambosoftware/cambo-admin'
import {
    CurrencyDollarIcon,
    ShoppingCartIcon,
    UsersIcon,
    ChartBarIcon
} from '@heroicons/vue/24/outline'

defineProps({
    stats: Object,
    revenueData: Object,
    recentOrders: Object,
    recentActivity: Array,
    topProducts: Array
})
</script>

<template>
    <AdminLayout title="Dashboard">
        <PageHeader
            title="Dashboard"
            subtitle="Welcome back! Here's what's happening today."
        >
            <template #actions>
                <Button variant="outline">Export</Button>
                <Button variant="primary">Add Widget</Button>
            </template>
        </PageHeader>

        <!-- Stats Grid -->
        <StatGrid :cols="4" class="mb-6">
            <StatCard
                title="Total Revenue"
                :value="stats.revenue"
                :change="stats.revenueChange"
                :trend="stats.revenueChange.startsWith('+') ? 'up' : 'down'"
                :icon="CurrencyDollarIcon"
                icon-color="primary"
            />
            <StatCard
                title="Orders"
                :value="stats.orders"
                :change="stats.ordersChange"
                :trend="stats.ordersChange.startsWith('+') ? 'up' : 'down'"
                :icon="ShoppingCartIcon"
                icon-color="success"
            />
            <StatCard
                title="Customers"
                :value="stats.customers"
                :change="stats.customersChange"
                :trend="stats.customersChange.startsWith('+') ? 'up' : 'down'"
                :icon="UsersIcon"
                icon-color="info"
            />
            <StatCard
                title="Conversion Rate"
                :value="stats.conversion"
                :change="stats.conversionChange"
                :trend="stats.conversionChange.startsWith('+') ? 'up' : 'down'"
                :icon="ChartBarIcon"
                icon-color="warning"
            />
        </StatGrid>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Revenue Chart -->
            <Card title="Revenue Overview" class="lg:col-span-2">
                <template #actions>
                    <select class="text-sm border-gray-200 rounded-lg">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </template>
                <LineChart
                    :labels="revenueData.labels"
                    :datasets="[
                        {
                            label: 'Revenue',
                            data: revenueData.values,
                            borderColor: '#6366f1'
                        }
                    ]"
                    fill
                    gradient
                    height="300"
                />
            </Card>

            <!-- Sales by Category -->
            <Card title="Sales by Category">
                <DonutChart
                    :labels="['Electronics', 'Clothing', 'Food', 'Other']"
                    :data="[45, 25, 20, 10]"
                    :colors="['#6366f1', '#22c55e', '#f59e0b', '#94a3b8']"
                    show-total
                    total-label="Total Sales"
                />
            </Card>
        </div>

        <!-- Tables and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Orders -->
            <Card title="Recent Orders" class="lg:col-span-2">
                <template #actions>
                    <Button variant="ghost" size="sm" href="/orders">View All</Button>
                </template>
                <DataTable
                    :resource="recentOrders"
                    :columns="[
                        { key: 'id', label: 'Order' },
                        { key: 'customer', label: 'Customer' },
                        { key: 'total', label: 'Total' },
                        { key: 'status', label: 'Status' }
                    ]"
                    :show-pagination="false"
                    compact
                >
                    <template #cell-customer="{ row }">
                        <div class="flex items-center gap-2">
                            <Avatar :src="row.customer.avatar" :name="row.customer.name" size="sm" />
                            <span>{{ row.customer.name }}</span>
                        </div>
                    </template>
                    <template #cell-status="{ value }">
                        <Badge
                            :variant="value === 'completed' ? 'success' : value === 'pending' ? 'warning' : 'default'"
                        >
                            {{ value }}
                        </Badge>
                    </template>
                </DataTable>
            </Card>

            <!-- Recent Activity -->
            <Card title="Recent Activity">
                <Timeline>
                    <TimelineItem
                        v-for="activity in recentActivity"
                        :key="activity.id"
                        :variant="activity.type"
                    >
                        <div class="flex items-center gap-2">
                            <Avatar :src="activity.user.avatar" size="xs" />
                            <span class="font-medium">{{ activity.user.name }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ activity.action }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ activity.time }}</p>
                    </TimelineItem>
                </Timeline>
            </Card>
        </div>

        <!-- Top Products -->
        <Card title="Top Products" class="mt-6">
            <template #actions>
                <Button variant="ghost" size="sm" href="/products">View All Products</Button>
            </template>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="product in topProducts"
                    :key="product.id"
                    class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg"
                >
                    <img
                        :src="product.image"
                        :alt="product.name"
                        class="w-16 h-16 rounded-lg object-cover"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ product.name }}</p>
                        <p class="text-sm text-gray-500">{{ product.sales }} sales</p>
                        <p class="text-sm font-medium text-indigo-600">{{ product.revenue }}</p>
                    </div>
                </div>
            </div>
        </Card>
    </AdminLayout>
</template>
```

## Laravel Controller

```php
// app/Http/Controllers/DashboardController.php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return inertia('Dashboard', [
            'stats' => $this->getStats(),
            'revenueData' => $this->getRevenueData(),
            'recentOrders' => $this->getRecentOrders(),
            'recentActivity' => $this->getRecentActivity(),
            'topProducts' => $this->getTopProducts(),
        ]);
    }

    private function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentRevenue = Order::where('created_at', '>=', $currentMonth)->sum('total');
        $lastRevenue = Order::whereBetween('created_at', [$lastMonth, $currentMonth])->sum('total');
        $revenueChange = $lastRevenue > 0
            ? round((($currentRevenue - $lastRevenue) / $lastRevenue) * 100, 1)
            : 0;

        return [
            'revenue' => '$' . number_format($currentRevenue, 0),
            'revenueChange' => ($revenueChange >= 0 ? '+' : '') . $revenueChange . '%',
            'orders' => number_format(Order::where('created_at', '>=', $currentMonth)->count()),
            'ordersChange' => '+8.2%',
            'customers' => number_format(User::where('created_at', '>=', $currentMonth)->count()),
            'customersChange' => '+15.3%',
            'conversion' => '3.2%',
            'conversionChange' => '-2.1%',
        ];
    }

    private function getRevenueData(): array
    {
        $data = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d')),
            'values' => $data->pluck('total'),
        ];
    }

    private function getRecentOrders()
    {
        return Order::with('customer')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($order) => [
                'id' => '#' . $order->id,
                'customer' => [
                    'name' => $order->customer->name,
                    'avatar' => $order->customer->avatar_url,
                ],
                'total' => '$' . number_format($order->total, 2),
                'status' => $order->status,
            ]);
    }

    private function getRecentActivity(): array
    {
        return [
            [
                'id' => 1,
                'user' => ['name' => 'John Doe', 'avatar' => '/avatars/john.jpg'],
                'action' => 'Created a new order #1234',
                'time' => '5 minutes ago',
                'type' => 'success',
            ],
            [
                'id' => 2,
                'user' => ['name' => 'Jane Smith', 'avatar' => '/avatars/jane.jpg'],
                'action' => 'Updated product "Wireless Headphones"',
                'time' => '1 hour ago',
                'type' => 'info',
            ],
            [
                'id' => 3,
                'user' => ['name' => 'Bob Wilson', 'avatar' => '/avatars/bob.jpg'],
                'action' => 'Completed milestone "Phase 1"',
                'time' => '3 hours ago',
                'type' => 'success',
            ],
        ];
    }

    private function getTopProducts(): array
    {
        return Product::select('products.*')
            ->selectRaw('COUNT(order_items.id) as sales_count')
            ->selectRaw('SUM(order_items.total) as total_revenue')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderByDesc('sales_count')
            ->take(4)
            ->get()
            ->map(fn($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image_url,
                'sales' => $product->sales_count,
                'revenue' => '$' . number_format($product->total_revenue, 0),
            ]);
    }
}
```

## Customization Tips

### Add Quick Actions Widget

```vue
<Card title="Quick Actions" class="mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <Button variant="outline" class="flex-col h-24" href="/orders/create">
            <ShoppingCartIcon class="h-6 w-6 mb-2" />
            New Order
        </Button>
        <Button variant="outline" class="flex-col h-24" href="/products/create">
            <CubeIcon class="h-6 w-6 mb-2" />
            Add Product
        </Button>
        <Button variant="outline" class="flex-col h-24" href="/customers/create">
            <UserPlusIcon class="h-6 w-6 mb-2" />
            Add Customer
        </Button>
        <Button variant="outline" class="flex-col h-24" href="/reports">
            <ChartBarIcon class="h-6 w-6 mb-2" />
            View Reports
        </Button>
    </div>
</Card>
```

### Add Welcome Banner

```vue
<div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-6 mb-6 text-white">
    <h2 class="text-2xl font-bold">Good morning, {{ user.name }}! 👋</h2>
    <p class="mt-1 opacity-90">Here's what's happening with your store today.</p>
    <div class="mt-4 flex gap-3">
        <Button variant="secondary">View Analytics</Button>
        <Button variant="ghost" class="text-white hover:bg-white/20">Learn More</Button>
    </div>
</div>
```
