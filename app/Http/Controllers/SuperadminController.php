<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Superadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SuperadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:superadmin')->except(['showLoginForm', 'login']);
    }

    public function showLoginForm()
    {
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::guard('superadmin')->attempt($credentials)) {
            $superadmin = Auth::guard('superadmin')->user();
            
            if (!$superadmin->is_active) {
                Auth::guard('superadmin')->logout();
                return back()->withErrors(['username' => 'Account is not active']);
            }

            $superadmin->update(['last_login_at' => now()]);
            $request->session()->regenerate();

            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('superadmin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }

    public function createAdmin()
    {
        return view('superadmin.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create admin user
        $admin = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user created successfully');
    }

    public function dashboard()
    {
        // Count admins and users
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        $totalUsers = User::count();  // This will count both admins and users
        
        return view('superadmin.dashboard', [
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'totalUsers' => $totalUsers,  // Include total users
        ]);
    }
    
    public function getUserStatistics()
    {
        // Get stats for API response
        $totalUsers = User::count();  // Total users (admins + regular users)
        $totalAdmins = User::where('role', 'admin')->count();
    
        return response()->json([
            'totalUsers' => $totalUsers,
            'totalAdmins' => $totalAdmins,
        ]);
    }

    public function toggleMaintenance()
    {
        $superadmin = auth()->user();
        $newStatus = $superadmin->toggleMaintenanceMode();
        
        return back()->with('success', 'Maintenance mode ' . ($newStatus ? 'enabled' : 'disabled'));
    }

    public function maintenanceSwitch()
    {
        // Your maintenance switch logic here
        return view('superadmin.maintenance');
    }

    public function maintenanceView()
    {
        $maintenanceMode = auth()->user()->maintenance_mode;
        return view('superadmin.maintenanceSwitch', [
            'maintenanceMode' => $maintenanceMode
        ]);
    }

    public function maintenanceToggle()
    {
        $superadmin = auth()->user();
        $newStatus = $superadmin->toggleMaintenanceMode();
        
        return redirect()->back()->with('success', 
            'Maintenance mode ' . ($newStatus ? 'enabled' : 'disabled') . ' successfully.');
    }
}