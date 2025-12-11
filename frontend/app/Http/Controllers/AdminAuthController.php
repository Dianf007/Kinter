<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $admin = Admin::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'Invalid credentials');
    }
    public function dashboard()
    {
        if (!Session::get('admin_id')) {
            return redirect()->route('admin.login');
        }
        return view('admin.dashboard');
    }
    public function logout()
    {
        Session::forget('admin_id');
        return redirect()->route('admin.login');
    }
}
