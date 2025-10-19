<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Store feedback (resident only)
    public function store(Request $request)
    {
        $data = $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $serviceRequest = ServiceRequest::findOrFail($data['service_request_id']);

        if ($user->id !== $serviceRequest->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $feedback = Feedback::create($data);
        return response()->json([
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback
        ], 201);
    }

    // ✅ Update feedback (resident only)
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback = Feedback::findOrFail($id);
        $serviceRequest = $feedback->serviceRequest;
        $user = $request->user();

        if ($user->id !== $serviceRequest->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $feedback->update($data);

        return response()->json([
            'message' => 'Feedback updated successfully',
            'feedback' => $feedback
        ]);
    }

    // View all feedback (admin only)
    public function index(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $feedback = Feedback::with('serviceRequest.resident')->paginate(10);
        return response()->json($feedback);
    }
}
