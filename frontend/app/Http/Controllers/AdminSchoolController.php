<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminSchoolController extends Controller
{
    public function select()
    {
        $role = (string) Session::get('admin_role', 'admin');
        abort_if($role === 'admin', 403);

        $allowedSchoolIds = $this->allowedSchoolIds($role);

        $availableSchoolsQuery = School::query()->orderBy('name');
        if ($role === 'superadmin') {
            $availableSchoolsQuery->whereIn('id', $allowedSchoolIds);
        }

        $availableSchools = $availableSchoolsQuery->get();
        $currentSchool = Session::get('admin_school_id') ? School::find((int) Session::get('admin_school_id')) : null;

        return view('admin.select-school', compact('availableSchools', 'currentSchool'));
    }

    public function switch(Request $request)
    {
        $request->validate([
            'school_id' => ['required', 'integer', 'exists:schools,id'],
        ]);

        $role = (string) Session::get('admin_role', 'admin');
        $schoolId = (int) $request->input('school_id');

        if ($role === 'admin') {
            abort(403);
        }

        $allowedSchoolIds = $this->allowedSchoolIds($role);
        abort_if(!in_array($schoolId, $allowedSchoolIds, true), 403);

        Session::put('admin_school_id', $schoolId);

        return redirect()->intended(route('admin.dashboard'));
    }

    private function allowedSchoolIds(string $role): array
    {
        if ($role === 'ultraadmin') {
            return School::query()->pluck('id')->map(fn ($id) => (int) $id)->all();
        }

        if ($role === 'superadmin') {
            return array_values(array_map('intval', array_filter((array) Session::get('admin_school_ids', []))));
        }

        return [];
    }
}
