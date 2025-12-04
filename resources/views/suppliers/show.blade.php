<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl">{{ $supplier->company_name }}</h2>
                <div>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-indigo-600">{{ __('Edit') }}</a>
                </div>
            </div>

            <dl>
                <dt class="font-semibold">{{ __('Contact Person') }}</dt>
                <dd class="mb-2">{{ $supplier->contact_person }}</dd>

                <dt class="font-semibold">{{ __('Email') }}</dt>
                <dd class="mb-2">{{ $supplier->email }}</dd>

                <dt class="font-semibold">{{ __('Phone') }}</dt>
                <dd class="mb-2">{{ $supplier->phone }}</dd>

                <dt class="font-semibold">{{ __('Address') }}</dt>
                <dd class="mb-2">{{ $supplier->address }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
