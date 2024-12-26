<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\NewMessageReceived;
use App\Events\MessageRead;

class MessageController extends Controller
{
    public function inbox()
    {
        $messages = Message::where('to_user_id', auth()->id())
                         ->where('is_archived', false)
                         ->with(['sender', 'attachments'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        return view('mail.inbox', compact('messages'));
    }

    public function sent()
    {
        $messages = Message::where('from_user_id', auth()->id())
                         ->with(['recipient', 'attachments'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);
        return view('mail.sent', compact('messages'));
    }

    public function compose()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('mail.compose', compact('users'));
    }

    public function send(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png' // 10MB max per file
        ]);

        // Create the message
        $message = Message::create([
            'from_user_id' => auth()->id(),
            'to_user_id' => $validated['to_user_id'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
        ]);

        // Handle multiple file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    // Generate a unique filename
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    
                    // Store the file
                    $path = $file->storeAs('attachments', $filename, 'public');
                    
                    // Create attachment record
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'filename' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                } catch (\Exception $e) {
                    // Log the error but continue with other files
                    \Log::error('File upload failed: ' . $e->getMessage());
                    continue;
                }
            }
        }

        // Broadcast the new message event
        broadcast(new NewMessageReceived($message))->toOthers();

        // Add debug logging
        \Log::info('Message sent and broadcast', [
            'message_id' => $message->id,
            'to_user' => $validated['to_user_id']
        ]);

        return redirect()->route('mail.sent')->with('success', 'Message sent successfully!');
    }

    public function show(Message $message)
    {
        // Check if user has permission to view this message
        if (auth()->id() !== $message->to_user_id && auth()->id() !== $message->from_user_id) {
            abort(403);
        }

        // Eager load relationships
        $message->load(['sender', 'recipient', 'attachments']);

        // If recipient is viewing, automatically mark as read
        if (auth()->id() === $message->to_user_id && !$message->read_at) {
            $message->update([
                'read_at' => now(),
                'is_read' => true
            ]);

            // Broadcast message read event
            broadcast(new MessageRead($message->to_user_id))->toOthers();
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
}
