@props(['title' => null])

<div class="min-h-screen flex">
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-indigo-700 to-indigo-500 text-white items-center justify-center p-8">
        <div class="max-w-md text-center">
            <div class="mb-6">
                <x-application-logo class="w-20 h-20 mx-auto text-white" />
            </div>
            <h1 class="text-3xl font-bold mb-2">{{ $title ?? config('app.name', 'App') }}</h1>
            <p class="text-indigo-100">Manage your industrial sales, inventory and orders from a single dashboard.</p>
        </div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center lg:hidden">
                <x-application-logo class="w-16 h-16 mx-auto text-gray-900" />
                <h2 class="mt-2 text-xl font-semibold">{{ $title ?? config('app.name', 'App') }}</h2>
            </div>

            <div class="bg-white shadow rounded-lg p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
