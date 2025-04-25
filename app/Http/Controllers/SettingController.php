<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.setting', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();

        // Pastikan user terautentikasi
        if (!$user) {
            return back()->with('error', 'User not authenticated!');
        }

        try {
            // Handle profile image
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                // if ($user->profile_image) {
                //     $oldImage = str_replace('/storage/', 'public/', $user->profile_image);
                //     Storage::delete($oldImage);
                // }

                // Store new image
                $imagePath = $request->file('profile_image')->store('public/profile_images');
                $user->profile_image = Storage::url($imagePath);
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updateSecurity(Request $request)
    {
        // Validate the input fields for security settings
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Check if the current password is correct
        if (!\Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update user password
        $user = Auth::user();
        $user->password = bcrypt($validated['new_password']);
        $user->save();

        // Redirect back with a success message
        return back()->with('success', 'Password updated successfully!');
    }
}
