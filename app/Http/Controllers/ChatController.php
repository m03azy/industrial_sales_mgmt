<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($conversation) {
                return $conversation->messages->first()->created_at ?? $conversation->created_at;
            });

        return view('chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $conversation->load(['messages.sender', 'userOne', 'userTwo']);
        
        // Mark messages as read
        $conversation->messages()->where('sender_id', '!=', Auth::id())->whereNull('read_at')->update(['read_at' => now()]);

        return view('chat.show', compact('conversation'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $request->validate([
            'body' => 'required|string',
        ]);

        $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back();
    }

    public function create(User $user)
    {
        // Check if conversation already exists
        $authId = Auth::id();
        $conversation = Conversation::where(function ($query) use ($authId, $user) {
            $query->where('user_one_id', $authId)->where('user_two_id', $user->id);
        })->orWhere(function ($query) use ($authId, $user) {
            $query->where('user_one_id', $user->id)->where('user_two_id', $authId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $authId,
                'user_two_id' => $user->id,
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    private function authorizeAccess(Conversation $conversation)
    {
        if ($conversation->user_one_id !== Auth::id() && $conversation->user_two_id !== Auth::id()) {
            abort(403);
        }
    }

    public function users()
    {
        $currentUserId = Auth::id();
        
        // Get all users except current user
        $users = User::where('id', '!=', $currentUserId)
            ->where('status', 'approved')
            ->orderBy('name')
            ->get();

        return view('chat.users', compact('users'));
    }
}
