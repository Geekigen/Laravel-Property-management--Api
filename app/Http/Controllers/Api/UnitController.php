<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::paginate(10);
        return response()->json($units);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'unit_type' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'square_footage' => 'required|numeric|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'status' => 'required|string|in:vacant,occupied,maintenance,reserved',
        ]);

        $unit = Unit::create($validatedData);
        return response()->json($unit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);

        $validatedData = $request->validate([
            'property_id' => 'sometimes|integer|exists:properties,id',
            'unit_number' => 'sometimes|string|max:255',
            'unit_type' => 'sometimes|string|max:255',
            'bedrooms' => 'sometimes|integer|min:0',
            'bathrooms' => 'sometimes|integer|min:0',
            'square_footage' => 'sometimes|numeric|min:0',
            'rent_amount' => 'sometimes|numeric|min:0',
            'features' => 'nullable|array',
            'status' => 'required|string|in:vacant,occupied,maintenance,reserved',
        ]);

        $unit->update($validatedData);
        return response()->json($unit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['message' => 'Unit deleted successfully']);
    }
}
