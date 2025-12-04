<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Content Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.content.categories') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
                    <p class="text-gray-500 mt-2">Manage product categories</p>
                </a>

                <a href="{{ route('admin.content.banners') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">Banners</h3>
                    <p class="text-gray-500 mt-2">Manage homepage banners</p>
                </a>

                <a href="{{ route('admin.content.faqs') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900">FAQs</h3>
                    <p class="text-gray-500 mt-2">Manage frequently asked questions</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
