<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTimesheet;
use App\Http\Requests\UpdateTimesheet;
use App\Http\Resources\TimesheetResource;
use App\Models\Timesheet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TimesheetController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TimesheetResource::collection(auth()->user()->timesheets()->filter()->paginate());
    }

    public function store(CreateTimesheet $request): JsonResponse
    {
        $timesheet = auth()->user()->timesheets()->create($request->validated());

        return response()->json([
            'message' => 'Timesheet entry created successfully!',
            'data' => new TimesheetResource($timesheet),
        ], 201);
    }

    public function show(Timesheet $timesheet): JsonResponse
    {
        return response()->json([
            'data' => new TimesheetResource($timesheet),
        ]);
    }

    public function update(UpdateTimesheet $request, Timesheet $timesheet): JsonResponse
    {
        $this->authorize('update', $timesheet);

        $timesheet->update($request->validated());

        return response()->json([
            'message' => 'Timesheet entry updated successfully!',
            'data' => new TimesheetResource($timesheet),
        ]);
    }

    public function destroy(Timesheet $timesheet): JsonResponse
    {
        $this->authorize('delete', $timesheet);

        $timesheet->delete();

        return response()->json(['message' => 'Timesheet entry deleted successfully'], 200);
    }
}
