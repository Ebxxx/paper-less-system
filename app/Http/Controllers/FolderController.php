<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Message;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $folder = Folder::create([
                'name' => $request->name,
                'user_id' => auth()->id()
            ]);

            $folder->loadCount('messages');

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'folder' => $folder
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $folder->loadCount('messages');
        $messages = $folder->messages()->with(['sender', 'attachments'])->paginate(15);
        return view('mail.folder', compact('folder', 'messages'));
    }

    public function addMessage(Request $request)
    {
        $validated = $request->validate([
            'message_id' => 'required|exists:messages,id',
            'folder_id' => 'required|exists:folders,id'
        ]);

        $folder = Folder::findOrFail($validated['folder_id']);
        
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $folder->messages()->attach($validated['message_id']);
        $folder->loadCount('messages');

        return response()->json([
            'success' => true,
            'messages_count' => $folder->messages_count
        ]);
    }

    public function removeMessage(Folder $folder, Message $message)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $folder->messages()->detach($message->id);
        $folder->loadCount('messages');

        return response()->json([
            'success' => true,
            'message' => 'Message removed from folder',
            'messages_count' => $folder->messages_count
        ]);
    }

    public function update(Request $request, Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $folder->update([
                'name' => $request->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Folder updated successfully',
                'folder' => $folder
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // Detach all messages first
            $folder->messages()->detach();
            // Then delete the folder
            $folder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete folder: ' . $e->getMessage()
            ], 500);
        }
    }
} 