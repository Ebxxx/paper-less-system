<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Models\Message;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::post('/messages', [MessageController::class, 'sendMessage']);
// Route::get('/messages/{sender_id}/{receiver_id}', [MessageController::class, 'getMessages']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->get('/unread-count', function () {
    return response()->json([
        'count' => auth()->user()->unreadMessages()->count()
    ]);
});

Route::get('/search-suggestions', function (Request $request) {
    $query = $request->get('q');
    $user = auth()->user();
    
    if (empty($query)) {
        return response()->json([]);
    }
    
    try {
        $messages = Message::where(function($q) use ($query, $user) {
            // Basic search
            $q->where(function($sq) use ($query) {
                $sq->where('subject', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            });
            
            // Only show messages that the user has access to
            $q->where(function($sq) use ($user) {
                $sq->where('to_user_id', $user->id)
                   ->orWhere('from_user_id', $user->id);
            });
        })
        ->with(['sender', 'attachments'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        return response()->json($messages->map(function($message) {
            return [
                'id' => $message->id,
                'subject' => $message->subject,
                'sender' => $message->sender->username,
                'preview' => Str::limit($message->content, 50),
                'has_attachments' => $message->attachments->count() > 0,
                'date' => $message->created_at->format('M d'),
                'is_unread' => is_null($message->read_at)
            ];
        }));
    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while searching'], 500);
    }
})->middleware(['auth:sanctum', 'web']);

