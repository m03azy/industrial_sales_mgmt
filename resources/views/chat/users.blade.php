<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Start a Conversation') }}
            </h2>
            <a href="{{ route('chat.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to Messages
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <input type="text" id="search" placeholder="Search users..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <ul class="divide-y divide-gray-200" id="users-list">
                        @foreach($users as $user)
                            <li class="user-item" data-name="{{ strtolower($user->name) }}" data-role="{{ strtolower($user->role) }}">
                                <a href="{{ route('chat.start', $user) }}" class="block hover:bg-gray-50 transition">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                                                            @elseif($user->role === 'factory') bg-blue-100 text-blue-800
                                                            @elseif($user->role === 'retailer') bg-green-100 text-green-800
                                                            @elseif($user->role === 'driver') bg-yellow-100 text-yellow-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ ucfirst($user->role) }}
                                                        </span>
                                                    </p>
                                                    @if($user->email)
                                                        <p class="text-xs text-gray-400 mt-1">{{ $user->email }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @if($users->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <p>No users available to message.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const userItems = document.querySelectorAll('.user-item');
            
            userItems.forEach(item => {
                const name = item.dataset.name;
                const role = item.dataset.role;
                
                if (name.includes(searchTerm) || role.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
