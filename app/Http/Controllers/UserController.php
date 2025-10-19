<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // List all users (admin only)
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $users = User::paginate(10);
        return response()->json($users);
    }

    // Show user profile
    public function show(User $user)
    {
        return response()->json($user);
    }

    // Update user (self or admin)
    public function update(Request $request, User $user)
    {
        $auth = $request->user();

        if ($auth->id !== $user->id && $auth->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|file|mimes:jpg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) Storage::disk('public')->delete($user->profile_photo);
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    // Delete user (admin only)
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->profile_photo) Storage::disk('public')->delete($user->profile_photo);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
