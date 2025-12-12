<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Session::get('admin_id')) {
            return redirect()->route('admin.login');
        }

        $currentRole = Session::get('admin_role', 'admin');
        if (!empty($roles) && !in_array($currentRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
