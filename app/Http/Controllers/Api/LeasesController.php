<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lease;
use Illuminate\Http\Request;

class LeasesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leases = Lease::paginate(10);
        return response()->json($leases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'rent_amount' => 'required|numeric',
            'security_deposit' => 'required|numeric',
            'payment_day' => 'required|integer',
            'lease_type' => 'required|in:month-to-month,fixed-term',
            'status' => 'required|in:active,expired,terminated,upcoming',
            'terms' => 'nullable|string',
        ]);

        $lease = Lease::create($validatedData);
        return response()->json($lease, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lease = Lease::findOrFail($id);
        return response()->json($lease);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'unit_id' => 'sometimes|integer',
            'tenant_id' => 'sometimes|integer',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'rent_amount' => 'sometimes|numeric',
            'security_deposit' => 'sometimes|numeric',
            'payment_day' => 'sometimes|integer',
            'lease_type' => 'required|in:month-to-month,fixed-term',
            'status' => 'required|in:active,expired,terminated,upcoming',
            'terms' => 'nullable|string',
        ]);

        $lease = Lease::findOrFail($id);
        $lease->update($validatedData);
        return response()->json($lease);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lease = Lease::findOrFail($id);
        $lease->delete();
        return response()->json(['message' => 'Lease deleted successfully']);
    }
}
