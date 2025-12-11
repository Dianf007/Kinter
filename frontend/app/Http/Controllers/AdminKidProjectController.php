<?php

namespace App\Http\Controllers;

use App\Models\KidProjectScratch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class AdminKidProjectController extends Controller
{
    protected function ensureAdmin()
    {
        if (!Session::get('admin_id')) {
            abort(redirect()->route('admin.login'));
        }
    }
    public function index(Request $request)
    {
        $this->ensureAdmin();
        $query = KidProjectScratch::query();
        $search = $request->get('q');
        $status = $request->get('status', 'active');
        if ($search) {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('scratch_id', 'like', "%{$search}%");
            });
        }
        if ($status !== 'all') {
            $today = Carbon::today();
            $query->where(function ($builder) use ($today) {
                $builder->whereNull('expired_dt')
                    ->orWhere('expired_dt', '>=', $today);
            });
        }
        $projects = $query->orderByRaw('COALESCE(expired_dt, "2099-12-31") asc')
            ->paginate(12)
            ->withQueryString();
        return view('admin.kid-projects.index', [
            'projects' => $projects,
            'search' => $search,
            'status' => $status,
        ]);
    }
    public function create()
    {
        $this->ensureAdmin();
        return view('admin.kid-projects.create');
    }
    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $this->validatedData($request);
        KidProjectScratch::create($data);
        return redirect()->route('admin.kid-projects.index')->with('success', 'Project created successfully.');
    }
    public function edit(KidProjectScratch $kid_project)
    {
        $this->ensureAdmin();
        return view('admin.kid-projects.edit', ['project' => $kid_project]);
    }
    public function update(Request $request, KidProjectScratch $kid_project)
    {
        $this->ensureAdmin();
        $data = $this->validatedData($request);
        $kid_project->update($data);
        return redirect()->route('admin.kid-projects.index')->with('success', 'Project updated successfully.');
    }
    public function destroy(KidProjectScratch $kid_project)
    {
        $this->ensureAdmin();
        $kid_project->delete();
        return redirect()->route('admin.kid-projects.index')->with('success', 'Project deleted.');
    }
    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'scratch_id' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'expired_dt' => 'nullable|date',
        ]);
        $validated['description'] = $validated['description'] ?? '';
        $validated['instructions'] = $validated['instructions'] ?? '';
        return $validated;
    }
}
