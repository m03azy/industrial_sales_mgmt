<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Revenue Chart Placeholder -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Monthly Revenue</h3>
                    <div class="h-64 bg-gray-100 flex items-center justify-center text-gray-500">
                        [Chart: Revenue Trend]
                        <!-- Integrate Chart.js here in real implementation -->
                    </div>
                    <div class="mt-4">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left">Month</th>
                                    <th class="text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyRevenue as $data)
                                    <tr>
                                        <td>{{ $data->month }}</td>
                                        <td class="text-right">${{ number_format($data->revenue, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- User Growth Chart Placeholder -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">User Growth</h3>
                    <div class="h-64 bg-gray-100 flex items-center justify-center text-gray-500">
                        [Chart: User Growth]
                    </div>
                    <div class="mt-4">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="text-left">Month</th>
                                    <th class="text-right">New Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userGrowth as $data)
                                    <tr>
                                        <td>{{ $data->month }}</td>
                                        <td class="text-right">{{ $data->count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Top Selling Products</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($topProducts as $product)
                            <li class="py-3 flex justify-between">
                                <span>{{ $product->name }}</span>
                                <span class="font-bold">{{ $product->total_sold }} sold</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Order Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Order Status Distribution</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($orderStatus as $status)
                            <li class="py-3 flex justify-between">
                                <span>{{ ucfirst($status->status) }}</span>
                                <span class="font-bold">{{ $status->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
