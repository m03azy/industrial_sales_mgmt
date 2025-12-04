<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Banners') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- List -->
                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Existing Banners</h3>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($banners as $banner)
                            <div class="border rounded p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ asset('storage/' . $banner->image) }}" class="w-20 h-12 object-cover rounded mr-4" alt="Banner">
                                    <div>
                                        <h4 class="font-bold">{{ $banner->title ?? 'No Title' }}</h4>
                                        <p class="text-sm text-gray-500">{{ $banner->link }}</p>
                                    </div>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $banners->links() }}</div>
                </div>

                <!-- Create Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Add Banner</h3>
                    <form action="{{ route('admin.content.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="image" class="mt-1 block w-full" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Link (Optional)</label>
                            <input type="url" name="link" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Order</label>
                            <input type="number" name="order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="0">
                        </div>
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                                <span class="ml-2 text-sm text-gray-600">Active</span>
                            </label>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-full">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
