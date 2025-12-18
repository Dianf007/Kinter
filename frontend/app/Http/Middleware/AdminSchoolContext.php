<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\School;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminSchoolContext
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('admin_id')) {
            return redirect()->route('admin.login');
        }

        $role = (string) Session::get('admin_role', 'admin');

        $allowedSchoolIds = $this->allowedSchoolIds($role);

        // For single-school admin, ensure session has school_id.
        if ($role === 'admin' && !Session::get('admin_school_id')) {
            $admin = Admin::find(Session::get('admin_id'));
            if ($admin?->school_id) {
                Session::put('admin_school_id', (int) $admin->school_id);
                $allowedSchoolIds = [(int) $admin->school_id];
            }
        }

        $currentSchoolId = Session::get('admin_school_id');

        // If no current school selected (typical for superadmin/ultraadmin), redirect to selector.
        if (!$currentSchoolId) {
            // avoid redirect loop
            if (!$request->routeIs('admin.school.*')) {
                return redirect()->route('admin.school.select');
            }

            $this->shareToViews(null, $allowedSchoolIds);
            return $next($request);
        }

        $currentSchoolId = (int) $currentSchoolId;

        // Validate selection is within allowed set (superadmin/ultraadmin).
        if (!empty($allowedSchoolIds) && !in_array($currentSchoolId, $allowedSchoolIds, true)) {
            Session::forget('admin_school_id');
            if (!$request->routeIs('admin.school.*')) {
                return redirect()->route('admin.school.select');
            }
        }

        $currentSchool = School::find($currentSchoolId);
        $this->shareToViews($currentSchool, $allowedSchoolIds);

        return $next($request);
    }

    private function allowedSchoolIds(string $role): array
    {
        if ($role === 'ultraadmin') {
            return School::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
        }

        if ($role === 'superadmin') {
            return array_values(array_map('intval', array_filter((array) Session::get('admin_school_ids', []))));
        }

        $schoolId = Session::get('admin_school_id');
        return $schoolId ? [(int) $schoolId] : [];
    }

    private function shareToViews(?School $currentSchool, array $allowedSchoolIds): void
    {
        $role = (string) Session::get('admin_role', 'admin');

        $availableSchoolsQuery = School::query()->orderBy('name');
        if ($role === 'superadmin') {
            $availableSchoolsQuery->whereIn('id', $allowedSchoolIds);
        }

        $availableSchools = $availableSchoolsQuery->get();

        view()->share([
            'currentSchool' => $currentSchool,
            'availableSchools' => $availableSchools,
        ]);
    }
}
