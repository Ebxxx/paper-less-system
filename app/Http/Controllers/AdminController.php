<?php
    
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $recentUsers = User::latest()->take(5)->get();
        
        // User type statistics
        $adminCount = User::where('role', 'admin')->count();
        $regularUserCount = User::where('role', 'user')->count();
        
        // Get distinct years
        $years = User::selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Default to the most recent year if no year is specified
        $selectedYear = request('year', $years->first());
        
        // Define month order
        $monthOrder = [
            'Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' => 4, 
            'May' => 5, 'Jun' => 6, 'Jul' => 7, 'Aug' => 8, 
            'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12
        ];
        
        // User growth data with year filtering
        $userGrowth = User::select('created_at')
            ->whereYear('created_at', $selectedYear)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('M');
            })
            ->map(function ($item) {
                return $item->count();
            })
            ->toArray();
    
        // Prepare user growth data for chart, sorted chronologically
        $userGrowthData = collect($userGrowth)
            ->map(function($users, $month) {
                return [
                    'month' => $month,
                    'users' => $users
                ];
            })
            ->sortBy(function($item) use ($monthOrder) {
                return $monthOrder[$item['month']];
            })
            ->values()
            ->toArray();
    
        // Message statistics
        $totalMessages = Message::count();
        $readMessages = Message::whereNotNull('read_at')->count();
        $unreadMessages = Message::whereNull('read_at')->count();
        $urgentMessages = Message::whereHas('marks', function($query) {
            $query->where('is_urgent', true);
        })->count();
        $importantMessages = Message::whereHas('marks', function($query) {
            $query->where('is_important', true);
        })->count();
        
        // Add total attachments count
        $totalAttachments = MessageAttachment::count();

        return view('admin.AdminDashboard', compact(
            'totalUsers', 
            'recentUsers', 
            'adminCount', 
            'regularUserCount', 
            'userGrowthData',
            'years',
            'selectedYear',
            'totalMessages',
            'readMessages',
            'unreadMessages',
            'urgentMessages',
            'importantMessages',
            'totalAttachments'
        ));
    }
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'signature' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'program' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255']
        ]);

        $userData = [
            'username' => $request->username,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'job_title' => $request->job_title,
            'program' => $request->program,
            'department' => $request->department
        ];

        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $userData['signature_path'] = $path;
        }

        $user = User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
{
    $request->validate([
        'username' => ['required', 'string', 'max:255'],
        'first_name' => ['required', 'string', 'max:255'],
        'middle_name' => ['nullable', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'role' => ['required', 'in:admin,user'],
        'signature' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
        'job_title' => ['nullable', 'string', 'max:255'],
        'program' => ['nullable', 'string', 'max:255'],
        'department' => ['nullable', 'string', 'max:255']
    ]);

    $userData = [
        // 'prefix' => $request->prefix,
        'username' => $request->username,
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'role' => $request->role,
        'job_title' => $request->job_title,
        'program' => $request->program,
        'department' => $request->department
    ];

    if ($request->filled('password')) {
        $request->validate([
            'password' => ['confirmed', Password::defaults()]
        ]);
        $userData['password'] = Hash::make($request->password);
    }

    if ($request->hasFile('signature')) {
        $path = $request->file('signature')->store('signatures', 'public');
        $userData['signature_path'] = $path;
    }

    $user->update($userData);

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully');
}

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function mailMonitoring(Request $request)
    {
        $query = Message::with(['sender', 'recipient', 'marks']);

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('sender', function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%");
                })
                ->orWhereHas('recipient', function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%");
                })
                ->orWhere('subject', 'like', "%{$search}%");

                // If search is a date
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
                    $q->orWhereDate('created_at', $search);
                }
            });
        }

        // Status filters
        if ($request->filled('status')) {
            $statuses = $request->input('status');
            foreach ($statuses as $status) {
                switch ($status) {
                    case 'read':
                        $query->whereNotNull('read_at');
                        break;
                    case 'unread':
                        $query->whereNull('read_at');
                        break;
                    case 'important':
                        $query->whereHas('marks', function($q) {
                            $q->where('is_important', true);
                        });
                        break;
                    case 'urgent':
                        $query->whereHas('marks', function($q) {
                            $q->where('is_urgent', true);
                        });
                        break;
                }
            }
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.mail.monitoring', compact('messages'));
    }

    public function getMessage(Message $message)
    {
        $message->load(['sender', 'recipient', 'attachments', 'marks']);
        
        return response()->json([
            'subject' => $message->subject,
            'content' => $message->content,
            'from_user' => [
                'username' => $message->sender->username,
                'full_name' => $message->sender->getFullnameAttribute(),
                'email' => $message->sender->email,
                'job_title' => $message->sender->job_title,
                'department' => $message->sender->department
            ],
            'to_user' => [
                'username' => $message->recipient->username,'email' => $message->recipient->email,
                'full_name' => $message->recipient->getFullnameAttribute(),
                'email' => $message->recipient->email,
                'job_title' => $message->recipient->job_title,
                'department' => $message->recipient->department
            ],
            'created_at' => $message->created_at->format('Y-m-d H:i'),
            'read_at' => $message->read_at ? $message->read_at->format('Y-m-d H:i') : null,
            'marks' => [
                'is_important' => $message->marks->first()?->is_important ?? false,
                'is_urgent' => $message->marks->first()?->is_urgent ?? false,
                'deadline' => $message->marks->first()?->deadline ? 
                    date('Y-m-d H:i', strtotime($message->marks->first()->deadline)) : null
            ],
            'attachments' => $message->attachments->map(function($attachment) {
                return [
                    'id' => $attachment->id,
                    'filename' => $attachment->filename,
                    'original_filename' => $attachment->original_filename,
                    'file_size' => $attachment->file_size,
                    'mime_type' => $attachment->mime_type,
                    'download_url' => route('admin.mail.download', $attachment)
                ];
            })
        ]);
    }

    public function downloadAttachment(MessageAttachment $attachment)
    {
        // Check if the user has permission to download this attachment
        $message = $attachment->message;
        
        // Get the full path to the file
        $path = storage_path('app/public/' . $attachment->filename);
        
        if (!file_exists($path)) {
            abort(404);
        }
        
        return response()->download($path, $attachment->original_filename);
    }
}
