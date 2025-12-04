<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h2 class="font-semibold text-xl mb-4">{{ __('Edit Supplier') }}</h2>

            @if($errors->any())
                <div class="mb-4 text-red-700">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block">{{ __('Company Name') }}</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="w-full border rounded px-2 py-1" required>
                </div>

                <div class="mb-4">
                    <label class="block">{{ __('Contact Person') }}</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="w-full border rounded px-2 py-1">
                </div>

                <div class="mb-4">
                    <label class="block">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full border rounded px-2 py-1">
                </div>

                <div class="mb-4">
                    <label class="block">{{ __('Phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full border rounded px-2 py-1">
                </div>

                <div class="mb-4">
                    <label class="block">{{ __('Address') }}</label>
                    <textarea name="address" class="w-full border rounded px-2 py-1">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn btn-primary">{{ __('Update') }}</button>
                    <a href="{{ route('suppliers.show', $supplier) }}" class="text-sm text-gray-600">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
