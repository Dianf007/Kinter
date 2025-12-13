<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    private function currentAdmin(): ?Admin
    {
        $adminId = Session::get('admin_id');
        if (!$adminId) {
            return null;
        }

        return Admin::find($adminId);
    }

    private function currentRole(): string
    {
        return Session::get('admin_role', 'admin');
    }

    private function managedSchoolIds(): array
    {
        $role = $this->currentRole();
        if ($role === 'ultraadmin') {
            return School::query()->pluck('id')->all();
        }

        if ($role === 'superadmin') {
            return array_values(array_filter((array) Session::get('admin_school_ids', [])));
        }

        return [];
    }

    private function ensureCanManageTarget(Admin $target): void
    {
        $role = $this->currentRole();

        if ($role === 'ultraadmin') {
            return;
        }

        if ($role === 'superadmin') {
            if (($target->role ?? 'admin') !== 'admin') {
                abort(403);
            }

            $allowedSchoolIds = $this->managedSchoolIds();
            if (!$target->school_id || !in_array($target->school_id, $allowedSchoolIds, true)) {
                abort(403);
            }

            return;
        }

        abort(403);
    }

    private function roleOptionsForCurrent(): array
    {
        $role = $this->currentRole();

        if ($role === 'ultraadmin') {
            return ['superadmin', 'admin'];
        }

        if ($role === 'superadmin') {
            return ['admin'];
        }

        return [];
    }

    private function availableSchoolsForCurrent()
    {
        $schoolIds = $this->managedSchoolIds();

        return School::query()
            ->when($this->currentRole() === 'superadmin', fn ($q) => $q->whereIn('id', $schoolIds))
            ->orderBy('name')
            ->get();
    }

    private function applyScopeAndFilters($query, Request $request)
    {
        $role = $this->currentRole();

        if ($role === 'superadmin') {
            $allowedSchoolIds = $this->managedSchoolIds();
            $query->where('role', 'admin')->whereIn('school_id', $allowedSchoolIds);
        }

        $filterRole = (string) $request->input('role', '');
        if ($filterRole !== '') {
            if ($role === 'superadmin') {
                abort_if($filterRole !== 'admin', 403);
            }
            $query->where('role', $filterRole);
        }

        $schoolId = $request->input('school_id');
        if ($schoolId !== null && $schoolId !== '') {
            $schoolId = (int) $schoolId;
            if ($role === 'superadmin' && !in_array($schoolId, $this->managedSchoolIds(), true)) {
                abort(403);
            }

            // admin is single school; superadmin is via pivot
            $query->where(function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId)
                    ->orWhereHas('managedSchools', fn ($mq) => $mq->where('schools.id', $schoolId));
            });
        }

        $q = trim((string) $request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('username', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            });
        }

        return $query;
    }

    public function index()
    {
        $role = $this->currentRole();

        $request = request();

        $query = Admin::query()->with(['school', 'managedSchools']);
        $this->applyScopeAndFilters($query, $request);

        if ($role === 'ultraadmin') {
            $query->orderByRaw("FIELD(role, 'ultraadmin','superadmin','admin')")
                ->orderBy('id', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $admins = $query->paginate(10)->withQueryString();
        $schools = $this->availableSchoolsForCurrent();

        $roleFilterOptions = $role === 'superadmin'
            ? ['admin']
            : ['admin', 'superadmin', 'ultraadmin'];

        return view('admin.admins.index', [
            'admins' => $admins,
            'schools' => $schools,
            'roleFilterOptions' => $roleFilterOptions,
            'currentRole' => $role,
        ]);
    }

    public function data(Request $request)
    {
        $role = $this->currentRole();

        $baseQuery = Admin::query()->with(['school', 'managedSchools']);
        // total BEFORE filters/search
        if ($role === 'superadmin') {
            $allowedSchoolIds = $this->managedSchoolIds();
            $baseQuery->where('role', 'admin')->whereIn('school_id', $allowedSchoolIds);
        }
        $recordsTotal = (clone $baseQuery)->count();

        // apply UI filters (role, school_id, q)
        $this->applyScopeAndFilters($baseQuery, $request);

        // apply DataTables global search (if any)
        $searchValue = trim((string) $request->input('search.value', ''));
        if ($searchValue !== '') {
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('username', 'like', "%{$searchValue}%")
                    ->orWhere('role', 'like', "%{$searchValue}%");
            });
        }

        $recordsFiltered = (clone $baseQuery)->count();

        $orderColIndex = (int) $request->input('order.0.column', 0);
        $orderDir = strtolower((string) $request->input('order.0.dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $columns = (array) $request->input('columns', []);
        $orderDataKey = $columns[$orderColIndex]['data'] ?? 'id';

        $orderMap = [
            'username' => 'username',
            'role' => 'role',
            'created_at' => 'created_at',
            'id' => 'id',
        ];
        $orderBy = $orderMap[$orderDataKey] ?? 'id';
        $baseQuery->orderBy($orderBy, $orderDir);

        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        if ($length < 1 || $length > 200) {
            $length = 10;
        }

        $admins = $baseQuery->skip($start)->take($length)->get();

        $data = $admins->map(function (Admin $admin) {
            $adminRole = $admin->role ?? 'admin';
            $schoolText = '-';
            if ($adminRole === 'admin') {
                $schoolText = $admin->school->name ?? '-';
            } elseif ($adminRole === 'superadmin') {
                $schoolText = $admin->managedSchools->pluck('name')->implode(', ');
                $schoolText = $schoolText ?: '-';
            }

            return [
                'id' => $admin->id,
                'username' => $admin->username,
                'role' => $adminRole,
                'school' => $schoolText,
                'created_at' => $admin->created_at ? $admin->created_at->format('Y-m-d H:i') : '-',
                'edit_url' => route('admin.admins.edit', $admin),
                'destroy_url' => route('admin.admins.destroy', $admin),
            ];
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $roleOptions = $this->roleOptionsForCurrent();
        $schoolIds = $this->managedSchoolIds();

        $schools = School::query()
            ->when($this->currentRole() === 'superadmin', fn ($q) => $q->whereIn('id', $schoolIds))
            ->orderBy('name')
            ->get();

        return view('admin.admins.create', [
            'roleOptions' => $roleOptions,
            'schools' => $schools,
            'currentRole' => $this->currentRole(),
        ]);
    }

    public function store(Request $request)
    {
        $roleOptions = $this->roleOptionsForCurrent();
        abort_if(empty($roleOptions), 403);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:admins,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in($roleOptions)],
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'school_ids' => ['array'],
            'school_ids.*' => ['integer', 'exists:schools,id'],
        ]);

        $role = $data['role'];
        $allowedSchoolIds = $this->managedSchoolIds();

        if ($role === 'admin') {
            if (empty($data['school_id'])) {
                return back()->withErrors(['school_id' => 'Sekolah wajib dipilih untuk role admin.'])->withInput();
            }
            if ($this->currentRole() === 'superadmin' && !in_array((int) $data['school_id'], $allowedSchoolIds, true)) {
                abort(403);
            }
        }

        if ($role === 'superadmin') {
            $schoolIds = array_values(array_unique(array_map('intval', $data['school_ids'] ?? [])));
            if (count($schoolIds) < 1) {
                return back()->withErrors(['school_ids' => 'Minimal pilih 1 sekolah untuk superadmin.'])->withInput();
            }
        }

        $admin = Admin::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $role,
            'school_id' => $role === 'admin' ? (int) $data['school_id'] : null,
        ]);

        if ($role === 'superadmin') {
            $schoolIds = array_values(array_unique(array_map('intval', $data['school_ids'] ?? [])));
            $admin->managedSchools()->sync($schoolIds);
        }

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dibuat.');
    }

    public function edit(Admin $admin)
    {
        $this->ensureCanManageTarget($admin);

        $roleOptions = $this->roleOptionsForCurrent();
        $schoolIds = $this->managedSchoolIds();

        $schools = School::query()
            ->when($this->currentRole() === 'superadmin', fn ($q) => $q->whereIn('id', $schoolIds))
            ->orderBy('name')
            ->get();

        $roleLocked = ($admin->role === 'ultraadmin') || ($admin->id === Session::get('admin_id'));

        return view('admin.admins.edit', [
            'admin' => $admin->load(['school', 'managedSchools']),
            'roleOptions' => $roleOptions,
            'schools' => $schools,
            'currentRole' => $this->currentRole(),
            'roleLocked' => $roleLocked,
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $this->ensureCanManageTarget($admin);

        $roleOptions = $this->roleOptionsForCurrent();
        abort_if(empty($roleOptions), 403);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('admins', 'username')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['nullable', Rule::in($roleOptions)],
            'school_id' => ['nullable', 'integer', 'exists:schools,id'],
            'school_ids' => ['array'],
            'school_ids.*' => ['integer', 'exists:schools,id'],
        ]);

        $isSelf = ((int) Session::get('admin_id') === (int) $admin->id);

        $newRole = $admin->role ?? 'admin';
        if (!$isSelf && $admin->role !== 'ultraadmin' && isset($data['role'])) {
            $newRole = $data['role'];
        }

        $allowedSchoolIds = $this->managedSchoolIds();

        if ($newRole === 'admin') {
            if (empty($data['school_id'])) {
                return back()->withErrors(['school_id' => 'Sekolah wajib dipilih untuk role admin.'])->withInput();
            }
            if ($this->currentRole() === 'superadmin' && !in_array((int) $data['school_id'], $allowedSchoolIds, true)) {
                abort(403);
            }

            $admin->school_id = (int) $data['school_id'];
            $admin->managedSchools()->sync([]);
        }

        if ($newRole === 'superadmin') {
            $schoolIds = array_values(array_unique(array_map('intval', $data['school_ids'] ?? [])));
            if (count($schoolIds) < 1) {
                return back()->withErrors(['school_ids' => 'Minimal pilih 1 sekolah untuk superadmin.'])->withInput();
            }

            $admin->school_id = null;
            $admin->managedSchools()->sync($schoolIds);
        }

        $admin->username = $data['username'];
        $admin->role = $newRole;

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diupdate.');
    }

    public function destroy(Admin $admin)
    {
        $this->ensureCanManageTarget($admin);

        if ((int) Session::get('admin_id') === (int) $admin->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        if (($admin->role ?? 'admin') === 'ultraadmin') {
            abort(403);
        }

        $admin->managedSchools()->sync([]);
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
