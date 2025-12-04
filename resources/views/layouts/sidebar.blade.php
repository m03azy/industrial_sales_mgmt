<div class="flex flex-col w-64 bg-indigo-900 h-screen fixed left-0 top-0 overflow-y-auto transition-transform transform -translate-x-full sm:translate-x-0 z-30" id="sidebar">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-indigo-950 border-b border-indigo-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
            <span class="text-white font-bold text-lg">SmartSupply</span>
        </a>
    </div>

    <!-- User Info -->
    <div class="px-6 py-4 border-b border-indigo-800">
        <div class="text-white font-medium truncate">{{ Auth::user()->name }}</div>
        <div class="text-indigo-300 text-xs truncate">{{ Auth::user()->email }}</div>
        <div class="mt-2 text-xs uppercase tracking-wider text-indigo-400 font-bold">{{ Auth::user()->role }}</div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-4 space-y-2">
        
        <!-- Common Links -->
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>{{ __('Dashboard') }}</span>
            </div>
        </x-nav-link>

        <x-nav-link :href="route('chat.index')" :active="request()->routeIs('chat.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('chat.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                <span>{{ __('Messages') }}</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </div>
        </x-nav-link>

        <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('notifications.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span>{{ __('Notifications') }}</span>
            </div>
        </x-nav-link>

        <!-- Admin Links -->
        @if(auth()->user()->role === 'admin')
            <div class="mt-4 mb-2 px-4 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Admin Management</div>
            
            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('products.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    {{ __('Products') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    {{ __('Orders') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    {{ __('Users') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.deliveries.index')" :active="request()->routeIs('admin.deliveries.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.deliveries.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Deliveries') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.drivers.index')" :active="request()->routeIs('admin.drivers.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.drivers.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0a2 2 0 012 2m9-2a2 2 0 012 2v0a2 2 0 012-2"></path></svg>
                    {{ __('Drivers') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.analytics.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    {{ __('Analytics') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.content.index')" :active="request()->routeIs('admin.content.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.content.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    {{ __('Content') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('admin.disputes.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ __('Disputes') }}
                </div>
            </x-nav-link>
        @endif

        <!-- Factory Links -->
        @if(auth()->user()->role === 'factory')
            <div class="mt-4 mb-2 px-4 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Factory Operations</div>
            
            <x-nav-link :href="route('factory.products.index')" :active="request()->routeIs('factory.products.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('factory.products.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    {{ __('My Products') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('factory.orders.index')" :active="request()->routeIs('factory.orders.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('factory.orders.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    {{ __('Orders') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('factory.profile.edit')" :active="request()->routeIs('factory.profile.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('factory.profile.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ __('Profile') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('factory.analytics.index')" :active="request()->routeIs('factory.analytics.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('factory.analytics.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    {{ __('Analytics') }}
                </div>
            </x-nav-link>
        @endif

        <!-- Retailer Links -->
        @if(auth()->user()->role === 'retailer')
            <div class="mt-4 mb-2 px-4 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Retailer Operations</div>
            
            <x-nav-link :href="route('retailer.products.index')" :active="request()->routeIs('retailer.products.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('retailer.products.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    {{ __('Marketplace') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('cart.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    {{ __('Cart') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('retailer.orders.index')" :active="request()->routeIs('retailer.orders.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('retailer.orders.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    {{ __('My Orders') }}
                </div>
            </x-nav-link>

            <x-nav-link :href="route('retailer.profile.edit')" :active="request()->routeIs('retailer.profile.*')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('retailer.profile.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ __('Profile') }}
                </div>
            </x-nav-link>
        @endif

        <!-- Driver Links -->
        @if(auth()->user()->role === 'driver')
            <div class="mt-4 mb-2 px-4 text-xs font-semibold text-indigo-400 uppercase tracking-wider">Driver Operations</div>
            
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block py-3 px-4 rounded transition duration-200 hover:bg-indigo-800 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 012-2v0a2 2 0 012 2m9-2a2 2 0 012 2v0a2 2 0 012-2"></path></svg>
                    {{ __('My Deliveries') }}
                </div>
            </x-nav-link>
        @endif

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-indigo-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full py-3 px-4 rounded transition duration-200 text-indigo-100 hover:bg-indigo-800 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
