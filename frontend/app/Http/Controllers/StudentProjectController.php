<?php

namespace App\Http\Controllers;

use App\Models\KidProjectScratch;
use Illuminate\Http\Request;

class StudentProjectController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user('api');

        // Get student's own projects + published projects from other students
        $projects = KidProjectScratch::where(function ($query) use ($student) {
            // Student's own projects
            $query->where('user_id', $student->id)
                // OR published projects from other students
                ->orWhere(function ($q) use ($student) {
                    $q->where('is_published', true)
                        ->where('user_id', '!=', $student->id);
                });
        })->with('student')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'projects' => $projects->map(function ($project) use ($student) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'instructions' => $project->instructions,
                    'scratch_id' => $project->scratch_id,
                    'is_published' => $project->is_published,
                    'is_owner' => $project->user_id === $student->id,
                    'created_at' => $project->created_at,
                    'student' => [
                        'id' => $project->student->id,
                        'name' => $project->student->name,
                        'avatar_url' => $project->student->avatar_url,
                    ],
                ];
            }),
        ]);
    }

    public function myProjects(Request $request)
    {
        $student = $request->user('api');

        $projects = $student->projects()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'projects' => $projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'description' => $project->description,
                    'instructions' => $project->instructions,
                    'scratch_id' => $project->scratch_id,
                    'is_published' => $project->is_published,
                    'is_expired' => $project->is_expired,
                    'expired_dt' => $project->expired_dt,
                    'created_at' => $project->created_at,
                ];
            }),
        ]);
    }

    public function show($id, Request $request)
    {
        $student = $request->user('api');
        $project = KidProjectScratch::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        // Check permission: owner can view, or public projects
        if ($project->user_id !== $student->id && !$project->is_published) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'project' => [
                'id' => $project->id,
                'title' => $project->title,
                'description' => $project->description,
                'instructions' => $project->instructions,
                'scratch_id' => $project->scratch_id,
                'is_published' => $project->is_published,
                'is_expired' => $project->is_expired,
                'expired_dt' => $project->expired_dt,
                'created_at' => $project->created_at,
                'is_owner' => $project->user_id === $student->id,
                'student' => [
                    'id' => $project->student->id,
                    'name' => $project->student->name,
                    'avatar_url' => $project->student->avatar_url,
                ],
            ],
        ]);
    }

    public function updatePublishStatus($id, Request $request)
    {
        $student = $request->user('api');
        $project = KidProjectScratch::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        if ($project->user_id !== $student->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'is_published' => 'required|boolean',
        ]);

        $project->update($validated);

        return response()->json([
            'message' => 'Project status updated',
            'project' => [
                'id' => $project->id,
                'title' => $project->title,
                'is_published' => $project->is_published,
            ]
        ]);
    }
}
