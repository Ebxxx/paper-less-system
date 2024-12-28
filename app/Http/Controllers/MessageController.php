<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\NewMessageReceived;
use App\Events\MessageRead;
use App\Models\MessageMark;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function inbox(Request $request)
    {
        $query = auth()->user()->receivedMessages()
            ->where('is_archived', false)
            ->with(['sender', 'mark', 'attachments']);

        $search = $request->get('search');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $messages = $query->latest()->paginate(10);
        
        return view('mail.inbox', compact('messages', 'search'));
    }

    public function sent(Request $request)
    {
        $query = auth()->user()->sentMessages()
            ->with(['recipient', 'mark']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('recipient', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $messages = $query->latest()->paginate(15);
        return view('mail.sent', compact('messages'));
    }

    public function compose()
    {
        // Only get non-admin users
        $users = User::where('id', '!=', auth()->id())
                     ->where('role', '!=', 'admin')
                     ->get();
        return view('mail.compose', compact('users'));
    }

    public function send(Request $request)
    {
        // Update validation for boolean fields
        $validated = $request->validate([
            'to_user_ids' => 'required|array',
            'to_user_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'sometimes|boolean',
            'is_urgent' => 'sometimes|boolean',
            'deadline' => 'nullable|date|after:now',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png'
        ]);

        $successCount = 0;
        $failCount = 0;

        // Send message to each recipient
        foreach ($validated['to_user_ids'] as $recipientId) {
            // Check if recipient is not an admin
            $recipient = User::find($recipientId);
            if ($recipient->role === 'admin') {
                continue; // Skip admin recipients
            }

            try {
                // Create the message
                $message = Message::create([
                    'from_user_id' => auth()->id(),
                    'to_user_id' => $recipientId,
                    'subject' => $validated['subject'],
                    'content' => $validated['content'],
                ]);

                // Create message mark with proper boolean values
                MessageMark::create([
                    'message_id' => $message->id,
                    'is_important' => (bool) $request->input('is_important', false),
                    'is_urgent' => (bool) $request->input('is_urgent', false),
                    'deadline' => $request->filled('deadline') ? $request->deadline : null
                ]);

                // Handle attachments if present
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $filename = uniqid() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('attachments', $filename, 'public');
                        
                        MessageAttachment::create([
                            'message_id' => $message->id,
                            'filename' => $path,
                            'original_filename' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                        ]);
                    }
                }

                // Broadcast new message event
                broadcast(new NewMessageReceived($message))->toOthers();
                $successCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send message: ' . $e->getMessage());
                $failCount++;
            }
        }

        // Prepare response message
        $message = '';
        if ($successCount > 0) {
            $message .= "Successfully sent to $successCount recipient(s). ";
        }
        if ($failCount > 0) {
            $message .= "Failed to send to $failCount recipient(s).";
        }

        return redirect()->route('mail.sent')->with('success', $message);
    }

    public function show(Message $message)
    {
        if (auth()->id() !== $message->to_user_id && auth()->id() !== $message->from_user_id) {
            abort(403);
        }

        // Mark message as read if recipient is viewing
        if (auth()->id() === $message->to_user_id && !$message->read_at) {
            $message->update(['read_at' => now()]);
            broadcast(new MessageRead($message))->toOthers();
        }

        return view('mail.show', compact('message'));
    }

    // Add these methods for the additional functionality
    public function toggleStar(Message $message)
    {
        // Check if user has permission
        if (auth()->id() !== $message->to_user_id && auth()->id() !== $message->from_user_id) {
            abort(403);
        }

        $message->update(['is_starred' => !$message->is_starred]);
        
        // Return JSON response for AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'is_starred' => $message->is_starred
            ]);
        }

        return back();
    }

    public function archive()
    {
        try {
            $messages = Message::where('to_user_id', auth()->id())
                             ->where('is_archived', true)
                             ->with(['sender', 'attachments'])
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);

            return view('mail.archive', compact('messages'));
        } catch (\Exception $e) {
            \Log::error('Archive error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load archived messages.');
        }
    }

    public function bulkArchive(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id'
        ]);

        Message::whereIn('id', $validated['ids'])
              ->where('to_user_id', auth()->id())
              ->update(['is_archived' => true]);

        return response()->json(['success' => true]);
    }

    public function bulkUnarchive(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:messages,id'
        ]);

        Message::whereIn('id', $validated['ids'])
              ->where('to_user_id', auth()->id())
              ->update(['is_archived' => false]);

        return response()->json(['success' => true]);
    }

    public function toggleArchive(Message $message)
    {
        if (auth()->id() !== $message->to_user_id) {
            abort(403);
        }

        $message->update(['is_archived' => !$message->is_archived]);
        return back();
    }

    public function download(MessageAttachment $attachment)
    {
        // Check if user has permission to download
        $message = $attachment->message;
        if (auth()->id() !== $message->to_user_id && auth()->id() !== $message->from_user_id) {
            abort(403);
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($attachment->filename)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download(
            $attachment->filename,
            $attachment->original_filename
        );
    }

    public function toggleRead(Message $message)
    {
        if (auth()->id() !== $message->to_user_id) {
            abort(403);
        }

        $message->update(['read_at' => $message->read_at ? null : now()]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'read' => !is_null($message->read_at)
            ]);
        }

        return back();
    }

    public function starred(Request $request)
    {
        $query = auth()->user()->receivedMessages()
            ->where('is_starred', true)
            ->with(['sender', 'mark']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($q) use ($search) {
                      $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $messages = $query->latest()->paginate(10);
        return view('mail.starred', compact('messages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'to_user_ids' => 'required|array',
            'to_user_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'is_important' => 'sometimes|boolean',
            'is_urgent' => 'sometimes|boolean',
            'deadline' => 'nullable|date|after:now',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png'
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($validated['to_user_ids'] as $recipientId) {
            try {
                DB::beginTransaction();

                // Create the message
                $message = Message::create([
                    'from_user_id' => auth()->id(),
                    'to_user_id' => $recipientId,
                    'subject' => $validated['subject'],
                    'content' => $validated['content'],
                ]);

                // Create message mark
                $mark = MessageMark::create([
                    'message_id' => $message->id,
                    'is_important' => $request->boolean('is_important', false),
                    'is_urgent' => $request->boolean('is_urgent', false),
                    'deadline' => $request->filled('deadline') ? $request->deadline : null
                ]);

                // Handle attachments if present
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $filename = uniqid() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('attachments', $filename, 'public');
                        
                        MessageAttachment::create([
                            'message_id' => $message->id,
                            'filename' => $path,
                            'original_filename' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                        ]);
                    }
                }

                DB::commit();

                // Load relationships including mark
                $message->load(['sender', 'mark', 'attachments']);

                // Broadcast new message event
                broadcast(new NewMessageReceived($message))->toOthers();
                $successCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to send message: ' . $e->getMessage());
                $failCount++;
            }
        }

        // Prepare response message
        $message = '';
        if ($successCount > 0) {
            $message .= "Successfully sent to $successCount recipient(s). ";
        }
        if ($failCount > 0) {
            $message .= "Failed to send to $failCount recipient(s).";
        }

        return redirect()->route('mail.sent')->with('success', $message);
    }
}
