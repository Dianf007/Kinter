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
            Session::put('admin_role', $admin->role ?? 'admin');
            Session::put('admin_school_id', $admin->school_id);
            // superadmin bisa punya banyak sekolah
            try {
                Session::put('admin_school_ids', $admin->managedSchools()->pluck('schools.id')->toArray());
            } catch (\Throwable $e) {
                Session::put('admin_school_ids', []);
            }
            $role = (string) Session::get('admin_role', 'admin');
            if (in_array($role, ['superadmin', 'ultraadmin'], true)) {
                // if no school selected yet, ask to pick one
                if (!Session::get('admin_school_id')) {
                    return redirect()->route('admin.school.select');
                }
            }

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
        Session::forget('admin_role');
        Session::forget('admin_school_id');
        Session::forget('admin_school_ids');
        return redirect()->route('admin.login');
    }
}
