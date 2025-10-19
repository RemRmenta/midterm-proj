<?php

namespace App\Http\Controllers;

use App\Models\RequestAssignment;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class RequestAssignmentController extends Controller
{
    // Assign worker (admin only)
    public function store(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'worker_id' => 'required|exists:users,id',
        ]);

        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $assignment = RequestAssignment::create([
            'service_request_id' => $request->service_request_id,
            'worker_id' => $request->worker_id,
            'assigned_at' => now(),
        ]);

        ServiceRequest::where('id', $request->service_request_id)->update(['status' => 'in_progress']);

        return response()->json(['message' => 'Worker assigned successfully', 'assignment' => $assignment], 201);
    }

    // Mark as completed
    public function complete(Request $request, RequestAssignment $assignment)
    {
        $user = $request->user();

        if ($user->id !== $assignment->worker_id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $assignment->markAsCompleted();

        return response()->json(['message' => 'Service request marked as completed']);
    }

    // View assignments (admin or worker)
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $assignments = RequestAssignment::with(['worker', 'serviceRequest'])->paginate(10);
        } else {
            $assignments = RequestAssignment::where('worker_id', $user->id)
                ->with(['serviceRequest'])
                ->paginate(10);
        }

        return response()->json($assignments);
    }
}
