<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceRequestController extends Controller
{
    // List all service requests
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $requests = ServiceRequest::with(['resident', 'assignment.worker'])->paginate(10);
        } elseif ($user->role === 'service_worker') {
            $requests = ServiceRequest::whereHas('assignment', fn($q) => $q->where('worker_id', $user->id))
                ->with(['resident', 'assignment.worker'])
                ->paginate(10);
        } else {
            $requests = ServiceRequest::where('user_id', $user->id)
                ->with(['assignment.worker'])
                ->paginate(10);
        }

        return response()->json($requests);
    }

    // Create a new service request (resident)
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'type' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'proof_of_service' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ]);

        $data['user_id'] = $user->id;

        if ($request->hasFile('proof_of_service')) {
            $data['proof_of_service'] = $request->file('proof_of_service')->store('proofs', 'public');
        }

        $serviceRequest = ServiceRequest::create($data);
        return response()->json(['message' => 'Service request created successfully', 'data' => $serviceRequest], 201);
    }

    // Show service request
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['resident', 'assignment.worker', 'feedback']);
        return response()->json($serviceRequest);
    }

    // Update service request (owner or admin)
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $user->id !== $serviceRequest->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'type' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'in:pending,in_progress,completed,cancelled',
            'priority' => 'in:low,medium,high',
            'proof_of_service' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ]);

        if ($request->hasFile('proof_of_service')) {
            if ($serviceRequest->proof_of_service) Storage::disk('public')->delete($serviceRequest->proof_of_service);
            $data['proof_of_service'] = $request->file('proof_of_service')->store('proofs', 'public');
        }

        $serviceRequest->update($data);

        return response()->json(['message' => 'Service request updated successfully', 'data' => $serviceRequest]);
    }

    // Delete service request
    public function destroy(Request $request, ServiceRequest $serviceRequest)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $user->id !== $serviceRequest->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($serviceRequest->proof_of_service) Storage::disk('public')->delete($serviceRequest->proof_of_service);
        $serviceRequest->delete();

        return response()->json(['message' => 'Service request deleted successfully']);
    }
}
