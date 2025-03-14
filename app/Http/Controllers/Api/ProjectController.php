<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProject;
use App\Http\Requests\UpdateProject;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Timesheet;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return ProjectResource::collection(Project::filter()->with(['users', 'timesheets', 'attributes.attribute'])->paginate());
    }

    public function store(CreateProject $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $project = Project::create([
                'name' => $validated['name'],
                'status' => $validated['status'],
            ]);
            foreach ($request->input('attributes', []) as $attributeData) {
                $project->attributes()->create([
                    'attribute_id' => $attributeData['attribute_id'],
                    'entity_id' => $project->id,
                    'value' => $attributeData['value'],
                ]);
            }
            if (! empty($validated['users'])) {
                $project->users()->sync($validated['users'] ?? []);
            }
            if (! empty($validated['timesheets'])) {
                Timesheet::whereIn('id', $validated['timesheets'])->update(['project_id' => $project->id]);
            }
            DB::commit();

            return response()->json([
                'message' => 'Project created successfully',
                'data' => new ProjectResource($project->load(['timesheets', 'users', 'attributes.attribute'])),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'data' => new ProjectResource($project->load(['users', 'timesheets', 'attributes.attribute'])),
        ]);
    }

    public function update(UpdateProject $request, Project $project): JsonResponse
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            $project->update([
                'name' => $validated['name'] ?? $project->name,
                'status' => $validated['status'] ?? $project->status,
            ]);

            if (isset($validated['attributes'])) {
                foreach ($validated['attributes'] as $attributeData) {
                    $project->attributes()->updateOrCreate(
                        [
                            'attribute_id' => $attributeData['attribute_id'],
                            'entity_id' => $project->id,
                        ],
                        [
                            'value' => $attributeData['value'],
                        ]
                    );
                }
            }

            if (isset($validated['users'])) {
                $project->users()->sync($validated['users']);
            }

            if (isset($validated['timesheets'])) {
                Timesheet::whereIn('id', $validated['timesheets'])
                    ->update(['project_id' => $project->id]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Project updated successfully',
                'data' => new ProjectResource($project->load(['timesheets', 'users', 'attributes.attribute'])),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to update project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }
}
