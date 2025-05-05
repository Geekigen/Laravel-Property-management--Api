<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index()
    {
        $properties = Cache::remember('properties_list', 60, function () {
            return Property::paginate(10);
        });

        return response()->json(['data' => $properties], 200);
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'property_type' => 'required|string|max:255',
            'year_built' => 'nullable|integer',
            'active' => 'required|boolean',
        ]);

        $property = Property::create($validatedData);


        Cache::forget('properties_list');

        return response()->json(['data' => $property], 201);
    }

    /**
     * Display the specified property.
     */
    public function show(string $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        return response()->json(['data' => $property], 200);
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, string $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        $validatedData = $request->validate([
            'user_id' => 'sometimes|integer|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:255',
            'postal_code' => 'sometimes|string|max:20',
            'country' => 'sometimes|string|max:255',
            'property_type' => 'sometimes|string|max:255',
            'year_built' => 'nullable|integer',
            'active' => 'sometimes|boolean',
        ]);

        $property->update($validatedData);
        Cache::forget('properties_list');

        return response()->json(['data' => $property], 200);
    }

    /**
     * Remove the specified property from storage.
     */
    public function destroy(string $id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        $property->delete();

        Cache::forget('properties_list');

        return response()->json(['message' => 'Property deleted successfully'], 200);
    }
}
