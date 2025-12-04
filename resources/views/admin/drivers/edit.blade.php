<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Driver') }} - {{ $driver->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.drivers.update', $driver) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vehicle Type -->
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                                <select name="vehicle_type" id="vehicle_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach(['Truck', 'Van', 'Motorcycle', 'Pickup'] as $type)
                                        <option value="{{ $type }}" {{ $driver->vehicle_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('vehicle_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- License Number -->
                            <div>
                                <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                                <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $driver->license_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('license_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Availability -->
                            <div>
                                <label for="is_available" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="is_available" id="is_available" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="1" {{ $driver->is_available ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ !$driver->is_available ? 'selected' : '' }}>Busy/Offline</option>
                                </select>
                                @error('is_available')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.drivers.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Update Driver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
