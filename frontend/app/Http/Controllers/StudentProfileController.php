<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function update(Request $request)
    {
        $student = $request->user('api');

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:students,email,' . $student->id,
            'gender' => 'sometimes|in:L,P',
            'birth_date' => 'sometimes|date|before:today',
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:500',
            'password' => 'sometimes|string|min:6|confirmed',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($student->avatar && \Storage::exists('public/' . $student->avatar)) {
                \Storage::delete('public/' . $student->avatar);
            }
            $path = $request->file('avatar')->store('avatars/students', 'public');
            $validated['avatar'] = $path;
        }

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $student->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'avatar_url' => $student->avatar_url,
                'gender' => $student->gender,
                'birth_date' => $student->birth_date,
                'phone' => $student->phone,
                'address' => $student->address,
            ]
        ]);
    }
}
