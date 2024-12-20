<?php
    
namespace App\Http\Controllers;

use App\Models\User;
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
    
        return view('admin.AdminDashboard', compact(
            'totalUsers', 
            'recentUsers', 
            'adminCount', 
            'regularUserCount', 
            'userGrowthData',
            'years',
            'selectedYear'
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
}
