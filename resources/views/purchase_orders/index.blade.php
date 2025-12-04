<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Purchase Orders') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">{{ __('Purchase Orders') }}</h3>
                    <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded">New PO</a>
                </div>

                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="py-2">{{ __('PO Number') }}</th>
                            <th class="py-2">{{ __('Supplier') }}</th>
                            <th class="py-2">{{ __('Date') }}</th>
                            <th class="py-2">{{ __('Total') }}</th>
                            <th class="py-2">{{ __('Status') }}</th>
                            <th class="py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $o)
                            <tr class="border-b">
                                <td class="py-2">{{ $o->order_number }}</td>
                                <td class="py-2">{{ $o->supplier->company_name ?? 'N/A' }}</td>
                                <td class="py-2">{{ $o->order_date }}</td>
                                <td class="py-2">${{ number_format($o->total_amount,2) }}</td>
                                <td class="py-2">{{ ucfirst($o->status) }}</td>
                                <td class="py-2">
                                    <a href="{{ route('purchase-orders.show', $o) }}" class="text-indigo-600">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
