<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Retailer Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('retailer.profile.update') }}">
                        @csrf

                        <!-- Company Name -->
                        <div class="mb-4">
                            <x-input-label for="company_name" :value="__('Company Name')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name', $retailer->company_name)" required />
                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                        </div>

                        <!-- Contact Person -->
                        <div class="mb-4">
                            <x-input-label for="contact_person" :value="__('Contact Person')" />
                            <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person', $retailer->contact_person)" required />
                            <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $retailer->phone)" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $retailer->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('address', $retailer->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Business Type -->
                        <div class="mb-4">
                            <x-input-label for="business_type" :value="__('Business Type')" />
                            <x-text-input id="business_type" class="block mt-1 w-full" type="text" name="business_type" :value="old('business_type', $retailer->business_type)" placeholder="e.g., Retail Store, Wholesale, Online Shop" />
                            <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
                        </div>

                        <!-- Preferred Delivery Method -->
                        <div class="mb-4">
                            <x-input-label for="preferred_delivery_method" :value="__('Preferred Delivery Method')" />
                            <select id="preferred_delivery_method" name="preferred_delivery_method" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Select...</option>
                                <option value="pickup" {{ old('preferred_delivery_method', $retailer->preferred_delivery_method) == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                <option value="delivery" {{ old('preferred_delivery_method', $retailer->preferred_delivery_method) == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                <option value="both" {{ old('preferred_delivery_method', $retailer->preferred_delivery_method) == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                            <x-input-error :messages="$errors->get('preferred_delivery_method')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dashboard.retailer') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Profile') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
