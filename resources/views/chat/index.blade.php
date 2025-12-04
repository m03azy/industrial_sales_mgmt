<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Messages') }}
            </h2>
            <a href="{{ route('chat.users') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                New Message
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($conversations->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mt-4">No messages yet.</p>
                            <a href="{{ route('chat.users') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-900">Start a conversation</a>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->user_one_id === auth()->id() ? $conversation->userTwo : $conversation->userOne;
                                    $lastMessage = $conversation->messages->first();
                                    $unreadCount = $conversation->messages->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count();
                                @endphp
                                <li>
                                    <a href="{{ route('chat.show', $conversation) }}" class="block hover:bg-gray-50 transition">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <p class="text-sm font-medium text-indigo-600">
                                                            {{ $otherUser->name }}
                                                            <span class="text-gray-500 text-xs">({{ ucfirst($otherUser->role) }})</span>
                                                        </p>
                                                        <p class="text-sm text-gray-500 truncate max-w-md">
                                                            {{ $lastMessage ? Str::limit($lastMessage->body, 50) : 'Start a conversation' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="ml-2 flex-shrink-0 flex items-center">
                                                    @if($unreadCount > 0)
                                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 mr-2">
                                                            {{ $unreadCount }} new
                                                        </span>
                                                    @endif
                                                    <p class="text-xs text-gray-500">
                                                        {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : 'New' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
