<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendPaymentReceipt;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments=Cache::remember('payments_list', 60, function () {
            return Payments::with('lease') // Eager load related models
                ->select(['id', 'lease_id', 'amount', 'due_date', 'payment_date', 'payment_method', 'transaction_id', 'status', 'notes'])
                ->paginate(10);
        });
        // $payments = Payments::with('lease')->paginate(10);
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'lease_id' => 'required|integer',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $payment = Payments::create($validatedData);
        SendPaymentReceipt::dispatch($payment);
        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payments::findOrFail($id);
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payments::findOrFail($id);

        $validatedData = $request->validate([
            'lease_id' => 'sometimes|required|integer',
            'amount' => 'sometimes|required|numeric',
            'due_date' => 'sometimes|required|date',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'sometimes|required|string',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validatedData);
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payments::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
