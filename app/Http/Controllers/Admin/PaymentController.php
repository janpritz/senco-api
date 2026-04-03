<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentStoreRequest;
use App\Models\Payment;
use App\Services\Admin\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache, Log};

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(PaymentStoreRequest $request)
    {
        $validated = $request->validated();  

        try {
            // The Service Layer handles the SENCO-XX-XXXXXX logic
            $payment = $this->paymentService->recordPayment($validated, Auth::id());

            return response()->json([
                'id'               => $payment->id,
                'student_id'       => $payment->student_id,
                'amount'           => (float) $payment->amount,
                'reference_number' => $payment->reference_number,
                'time'             => $payment->created_at->format('h:i A'),
                'date'             => $payment->created_at->toDateString(),
                'full_name'        => $request->full_name ?? 'Student',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $payments = Payment::with('student')
            ->latest()
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'student_id' => $payment->student_id,
                    'full_name' => $payment->student->full_name ?? 'Unknown Student',
                    'amount' => (float)$payment->amount,
                    // Ensure we use the exact DB column name here
                    'reference_number' => $payment->reference_number ?? 'REF-' . $payment->id,
                    // Send ISO 8601 string so JavaScript can parse it easily
                    'created_at' => $payment->created_at->toIso8601String(),
                ];
            });

        return response()->json($payments);
    }
}
