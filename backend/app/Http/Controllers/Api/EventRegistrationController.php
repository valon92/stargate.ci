<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EventRegistrationService;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventRegistrationController extends Controller
{
    protected EventRegistrationService $registrationService;

    public function __construct(EventRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Register for an event
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'preferences' => 'nullable|array',
            'timezone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user is authenticated
            $user = $request->user();
            $subscriberId = null;
            
            if ($user) {
                $subscriber = \App\Models\Subscriber::where('email', $user->email)->first();
                $subscriberId = $subscriber?->id;
            }

            // Check if already registered
            $existingRegistration = EventRegistration::where('event_id', $request->event_id)
                ->where('email', $request->email)
                ->first();
                
            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered for this event.',
                    'data' => [
                        'registration_id' => $existingRegistration->id,
                        'status' => $existingRegistration->status
                    ]
                ], 409);
            }

            // Prepare registration data
            $registrationData = $request->only(['email', 'name', 'phone', 'preferences']);
            $registrationData['subscriber_id'] = $subscriberId;
            
            if ($request->timezone) {
                $registrationData['preferences'] = array_merge(
                    $registrationData['preferences'] ?? [],
                    ['timezone' => $request->timezone]
                );
            }
            
            if ($request->notes) {
                $registrationData['preferences'] = array_merge(
                    $registrationData['preferences'] ?? [],
                    ['notes' => $request->notes]
                );
            }

            $registration = $this->registrationService->registerForEvent(
                $request->event_id,
                $registrationData
            );

            $registration->load('event');

            return response()->json([
                'success' => true,
                'message' => 'Successfully registered for the event! You will receive a confirmation email shortly.',
                'data' => [
                    'registration_id' => $registration->id,
                    'event' => $registration->event,
                    'registered_at' => $registration->registered_at,
                    'status' => $registration->status
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Event registration failed', [
                'event_id' => $request->event_id,
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get event registration statistics
     */
    public function stats(int $eventId): JsonResponse
    {
        try {
            $stats = $this->registrationService->getEventStats($eventId);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }
    }

    /**
     * Cancel a registration
     */
    public function cancel(int $registrationId): JsonResponse
    {
        try {
            $this->registrationService->cancelRegistration($registrationId);

            return response()->json([
                'success' => true,
                'message' => 'Registration cancelled successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }
    }

    /**
     * Get user's upcoming events
     */
    public function userEvents(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is required',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $events = $this->registrationService->getUserUpcomingEvents($request->email);

            return response()->json([
                'success' => true,
                'data' => $events
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user events'
            ], 500);
        }
    }

    /**
     * Check if user is registered for an event
     */
    public function checkRegistration(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if event exists
        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $registration = EventRegistration::where('event_id', $request->event_id)
            ->where('email', $request->email)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'is_registered' => $registration ? true : false,
                'registration' => $registration,
                'status' => $registration ? $registration->status : null
            ]
        ]);
    }

    /**
     * Send test reminder email (for testing purposes)
     */
    public function sendTestReminder(int $registrationId): JsonResponse
    {
        try {
            $registration = EventRegistration::findOrFail($registrationId);
            $this->registrationService->sendReminderEmail($registration);

            return response()->json([
                'success' => true,
                'message' => 'Test reminder sent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test reminder'
            ], 500);
        }
    }
}