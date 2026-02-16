<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $student = Student::where('email', $credentials['email'])->first();

        if (!$student || !Hash::check($credentials['password'], $student->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $student->createToken('student-token')->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'avatar_url' => $student->avatar_url,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();
        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function me(Request $request)
    {
        $student = $request->user('api');
        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'email' => $student->email,
            'student_id' => $student->student_id,
            'avatar_url' => $student->avatar_url,
            'gender' => $student->gender,
            'birth_date' => $student->birth_date,
            'phone' => $student->phone,
            'address' => $student->address,
        ]);
    }
}
