<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['reports', 'checkIns', 'posts', 'badges']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['reports', 'checkIns', 'posts', 'badges', 'helpOffers', 'verifications']);

        $stats = [
            'total_reports'         => $user->reports()->count(),
            'verified_reports'      => $user->reports()->where('status', 'verified')->count(),
            'pending_reports'       => $user->reports()->where('status', 'pending')->count(),
            'resolved_reports'      => $user->reports()->where('status', 'resolved')->count(),
            'total_check_ins'       => $user->checkIns()->count(),
            'total_posts'           => $user->posts()->count(),
            'total_verifications'   => $user->verifications()->count(),
            'total_help_offers'     => $user->helpOffers()->count(),
            'total_badges'          => $user->badges()->count()
        ];

        $recentActivity = [
            'reports'   => $user->reports()->latest()->limit(5)->get(),
            'check_ins' => $user->checkIns()->latest()->limit(5)->get(),
            'posts'     => $user->posts()->latest()->limit(5)->get()
        ];

        return view('admin.users.show', compact('user', 'stats', 'recentActivity'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:8|confirmed',
            'phone_number'      => 'nullable|string|max:20',
            'role'              => 'required|in:user,volunteer,official,admin',
            'is_verified'       => 'boolean',
            'is_active'         => 'boolean',
            'reputation_score'  => 'nullable|integer|min:0'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.users.create', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone_number'      => 'nullable|string|max:20',
            'role'              => 'required|in:user,volunteer,official,admin',
            'is_verified'       => 'boolean',
            'is_active'         => 'boolean',
            'reputation_score'  => 'nullable|integer|min:0',
        ]);

        // If password is provided, update it
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->user_id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    public function updateReputation(Request $request, User $user)
    {
        $validated = $request->validate([
            'reputation_score' => 'required|integer|min:0|max:10000',
        ]);

        $user->update($validated);

        return back()->with('success', 'Reputation score updated!');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully!");
    }

    public function verifyUser(User $user)
    {
        $user->update(['is_verified' => true]);
        return back()->with('success', 'User verified successfully!');
    }
}
