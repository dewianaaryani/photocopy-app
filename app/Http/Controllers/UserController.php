<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userProfile()   {
        $user = Auth::user();
        
        // You can then return a view with the user data
        return view('users.profile.edit', ['user' => $user]);
     
    }
    public function updateUser(Request $request, User $user)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->user()->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        // Retrieve the authenticated user
        $user = $request->user();
    
        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Check if password is provided and update it
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
    
        $user->save();
    
        // Redirect back with success message
        return redirect()->back()->with('message', 'Profile updated successfully!');
    }
}
