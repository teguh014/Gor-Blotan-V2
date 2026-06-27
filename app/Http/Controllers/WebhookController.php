<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function xendit(Request $request)
    {
        // Xendit sends x-callback-token in headers
        $xenditXCallbackToken = $request->header('x-callback-token');
        
        // Verify token matches the one in our .env
        if ($xenditXCallbackToken !== env('XENDIT_WEBHOOK_TOKEN')) {
            Log::warning('Xendit webhook token mismatch.', [
                'received' => $xenditXCallbackToken,
                'expected' => env('XENDIT_WEBHOOK_TOKEN')
            ]);
            return response()->json(['message' => 'Invalid token'], 403);
        }

        // Get payload
        $payload = $request->all();
        $invoiceId = $payload['id'] ?? null;
        $status = $payload['status'] ?? null;

        Log::info('Xendit Webhook Received', ['invoice_id' => $invoiceId, 'status' => $status]);

        if (!$invoiceId || !$status) {
            return response()->json(['message' => 'Bad request'], 400);
        }

        // Find booking by xendit_invoice_id
        $booking = Booking::where('xendit_invoice_id', $invoiceId)->first();

        if (!$booking) {
            Log::warning('Booking not found for Xendit Invoice', ['invoice_id' => $invoiceId]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update status based on webhook payload
        if ($status === 'PAID' || $status === 'SETTLED') {
            $booking->update(['status' => 'completed']);
        } elseif ($status === 'EXPIRED') {
            $booking->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'Success']);
    }
}
