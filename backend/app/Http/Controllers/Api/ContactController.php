<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Mail\ContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactMessage = ContactMessage::create($validated);

        // Send email notification to admin
        try {
            \Log::info('Attempting to send contact email for message ID: ' . $contactMessage->id);
            Mail::to('svalon95@gmail.com')->send(new ContactMessageNotification($contactMessage));
            \Log::info('Contact email sent successfully for message ID: ' . $contactMessage->id);
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Failed to send contact message email: ' . $e->getMessage());
            \Log::error('Email error details: ' . $e->getFile() . ':' . $e->getLine());
        }

        return response()->json([
            'message' => 'Thank you for your message! We\'ll get back to you soon.',
            'data' => $contactMessage
        ], 201);
    }

    /**
     * Display a listing of contact messages (admin only).
     */
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);

        return response()->json($messages);
    }

    /**
     * Display the specified contact message (admin only).
     */
    public function show(string $id)
    {
        $message = ContactMessage::findOrFail($id);

        return response()->json($message);
    }

    /**
     * Mark a contact message as read (admin only).
     */
    public function markAsRead(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['read' => true]);

        return response()->json($message);
    }

    /**
     * Remove the specified contact message (admin only).
     */
    public function destroy(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json(null, 204);
    }
}
