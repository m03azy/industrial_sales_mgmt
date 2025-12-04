<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat with') }} {{ $conversation->user_one_id === auth()->id() ? $conversation->userTwo->name : $conversation->userOne->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-[600px]">
                <!-- Messages Area -->
                <div class="flex-1 p-6 overflow-y-auto flex flex-col space-y-4" id="messages-container">
                    @foreach($conversation->messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                                <p class="text-sm">{{ $message->body }}</p>
                                <p class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-gray-500' }}">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Input Area -->
                <div class="border-t p-4 bg-gray-50">
                    <form action="{{ route('chat.store', $conversation) }}" method="POST" class="flex space-x-4">
                        @csrf
                        <input type="text" name="body" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Type your message..." required autofocus>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom on load
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    </script>
</x-app-layout>
